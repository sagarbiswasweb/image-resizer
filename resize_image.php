<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['imagePath']) && isset($_POST['width']) && isset($_POST['height'])) {
    // Get the original image path
    $imagePath = $_POST['imagePath'];
    $width = $_POST['width'];
    $height = $_POST['height'];

    // Get image info and determine the type
    $imageInfo = getimagesize($imagePath);
    $imageType = $imageInfo[2];

    // Load the image based on its type
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($imagePath);
            break;
        case IMAGETYPE_PNG:
            $image = imagecreatefrompng($imagePath);
            break;
        case IMAGETYPE_GIF:
            $image = imagecreatefromgif($imagePath);
            break;
        case IMAGETYPE_WEBP:
            $image = imagecreatefromwebp($imagePath);
            break;
        case IMAGETYPE_BMP:
            $image = imagecreatefrombmp($imagePath);
            break;
        default:
            echo "Unsupported image type!";
            exit;
    }

    // Resize the image
    $resizedImage = imagescale($image, $width, $height);

    // Save the resized image to the server
    // $resizedPath = 'uploads/resized_' . basename($imagePath);
    $resizedPath = 'uploads/resized_' . time() . '_' . basename($imagePath);

    switch ($imageType) {
        case IMAGETYPE_JPEG:
            imagejpeg($resizedImage, $resizedPath);
            break;
        case IMAGETYPE_PNG:
            imagepng($resizedImage, $resizedPath);
            break;
        case IMAGETYPE_GIF:
            imagegif($resizedImage, $resizedPath);
            break;
        case IMAGETYPE_WEBP:
            imagewebp($resizedImage, $resizedPath);
            break;
        case IMAGETYPE_BMP:
            imagebmp($resizedImage, $resizedPath);
            break;
    }

    // Free memory
    imagedestroy($image);
    imagedestroy($resizedImage);

    // Display the resized image
    ?>
    <div class="container-fluid px-2 d-flex justify-content-center">
        <div class="card shadow-sm border-0 w-100" style="margin-top: 20px;">
            <div class="card-body text-center">
                <h2 class="card-title h5 mb-3 text-secondary">Resized Image</h2>
                <img src="<?php echo htmlspecialchars($resizedPath); ?>" alt="Resized Image" 
                     class="img-fluid rounded" 
                     style="max-height: 300px; object-fit: contain; border: 1px solid #ddd; padding: 5px;">
                <div class="mt-4">
                    <a href="<?php echo htmlspecialchars($resizedPath); ?>" download class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-download"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php
} else {
    echo "Invalid request!";
}
?>
