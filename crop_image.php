<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<?php
// Check if the form is submitted and the cropped image data is received
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['croppedImage'])) {
    // Get the base64 encoded image
    $croppedImageData = $_POST['croppedImage'];

    // Remove the data URL prefix (data:image/jpeg;base64,)
    $croppedImageData = str_replace('data:image/jpeg;base64,', '', $croppedImageData);
    $croppedImageData = str_replace('data:image/png;base64,', '', $croppedImageData);
    $croppedImageData = str_replace('data:image/gif;base64,', '', $croppedImageData);

    // Decode the base64 image
    $croppedImageData = base64_decode($croppedImageData);

    // Save the cropped image to the server
    $fileName = 'cropped_image_' . time() . '.jpg'; // You can change the extension depending on the format
    $filePath = 'uploads/' . $fileName; // Ensure the "uploads" directory exists and is writable

    // Create the uploads directory if it doesn't exist
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    // Save the image
    file_put_contents($filePath, $croppedImageData);

    ?>
    <div class="container-fluid px-2 d-flex justify-content-center">
        <div class="card shadow-sm border-0 w-100" style="margin-top: 20px;">
            <div class="card-body text-center">
                <h2 class="card-title h5 mb-3 text-secondary">Cropped Image</h2>
                <img src="<?php echo htmlspecialchars($filePath); ?>" alt="Cropped Image" 
                     class="img-fluid rounded" 
                     style="max-height: 300px; object-fit: contain; border: 1px solid #ddd; padding: 5px;">
                <div class="mt-4">
                    <a href="<?php echo htmlspecialchars($filePath); ?>" download class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-download"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php
} else {
    echo '<p>No image data received. Please crop the image first.</p>';
}
?>
