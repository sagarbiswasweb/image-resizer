<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<?php
session_start();

// Initialize session variables if they are not set
if (!isset($_SESSION['rotation_angle'])) {
    $_SESSION['rotation_angle'] = 0; // Default rotation angle
}
if (!isset($_SESSION['modified_image'])) {
    $_SESSION['modified_image'] = ''; // No modified image yet
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    $originalImagePath = $_POST['imagePath'] ?? '';

    // Check if the original image exists
    if (!file_exists($originalImagePath)) {
        die("Image not found!");
    }

    // Use the original image as a base
    $imagePath = $originalImagePath;

    // Only modify the image if a new action is requested
    if ($action) {
        // Load the image from the current path
        $image = imagecreatefromstring(file_get_contents($imagePath));

        if (!$image) {
            die("Failed to load the image. Please ensure it's a supported format.");
        }

        // Perform the requested action
        switch ($action) {
            case 'flip_horizontal':
                if (!imageflip($image, IMG_FLIP_HORIZONTAL)) {
                    die("Failed to flip the image horizontally.");
                }
                break;
            case 'flip_vertical':
                if (!imageflip($image, IMG_FLIP_VERTICAL)) {
                    die("Failed to flip the image vertically.");
                }
                break;
            case 'rotate_clockwise':
                $_SESSION['rotation_angle'] -= 90; // Clockwise decreases angle
                break;
            case 'rotate_counter_clockwise':
                $_SESSION['rotation_angle'] += 90; // Counter-clockwise increases angle
                break;
            default:
                die("Invalid action: " . htmlspecialchars($action));
        }

        // Normalize the rotation angle to avoid excessive rotations
        $_SESSION['rotation_angle'] = $_SESSION['rotation_angle'] % 360;

        // Apply rotation if needed
        if ($action === 'rotate_clockwise' || $action === 'rotate_counter_clockwise') {
            $image = imagerotate($image, $_SESSION['rotation_angle'], 0);
        }

        // Generate a unique temporary file path for the modified image
        $uniqueId = uniqid('modified_', true);
        $tempImagePath = 'uploads/' . $uniqueId . '.jpg'; // Create a unique image path

        // Save the modified image to the temporary file
        if (!imagejpeg($image, $tempImagePath)) {
            die("Failed to save the modified image.");
        }

        // Ensure the file permissions allow access
        chmod($tempImagePath, 0644);

        // Destroy the image resource
        imagedestroy($image);

        // Store the path of the modified image in the session
        $_SESSION['modified_image'] = $tempImagePath;
    }

    // Store the timestamp for cache busting (this stays the same after image modification)
    $timestamp = time();
    $_SESSION['image_timestamp'] = $timestamp;

    // Display the image with a modern design
    ?>
    <div class="container-fluid px-2 d-flex justify-content-center">
        <div class="card shadow-sm border-0 w-100" style="margin-top: 20px;">
            <div class="card-body text-center">
                <h2 class="card-title h5 mb-3 text-secondary">Modified Image</h2>
                <img src="<?php echo htmlspecialchars($_SESSION['modified_image'] . "?t=" . $_SESSION['image_timestamp']); ?>" 
                     alt="Modified Image" 
                     class="img-fluid rounded" 
                     style="max-height: 300px; object-fit: contain; border: 1px solid #ddd; padding: 5px;">
                <div class="mt-4">
                    <!-- The download link now points to the session-stored image with timestamp for cache busting -->
                    <a href="<?php echo htmlspecialchars($_SESSION['modified_image'] . "?t=" . $_SESSION['image_timestamp']); ?>" 
                       download class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-download"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php
} else {
    // If no action, display the original image
    if ($_SESSION['modified_image']) {
        echo '<img src="' . $_SESSION['modified_image'] . '" alt="Modified Image">';
    }
}
?>
