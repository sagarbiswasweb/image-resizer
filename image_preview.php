<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['image'])) {
    // Save the uploaded image temporarily
    $image = $_FILES['image'];
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($image['name']);

    // Ensure the uploads directory exists
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }



    // Move the uploaded image to the server's upload directory
    if (move_uploaded_file($image['tmp_name'], $uploadFile)) {
        $imagePath = $uploadFile;
    } else {
        echo "File upload failed!";
        exit;
    }
} else {
    echo "No image uploaded!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Preview, Resize, and Crop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/custom.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="mb-4 text-center">Preview, Resize, and Crop Image</h2>

                <!-- Live preview area -->
                <div class="img-container mt-4">
                    <div id="image-container">
                        <img id="imageToCrop" src="<?php echo htmlspecialchars($imagePath); ?>" alt="Image to Crop">
                    </div>
                </div>

                <!-- Bootstrap Nav Tabs -->
                <ul class="nav nav-pills mb-4" id="imageTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active text-uppercase" id="resize-tab" data-bs-toggle="tab" data-bs-target="#resize" type="button" role="tab" aria-controls="resize" aria-selected="true">
                            <i class="fas fa-arrows-alt-h"></i> Resize
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-uppercase" id="crop-tab" data-bs-toggle="tab" data-bs-target="#crop" type="button" role="tab" aria-controls="crop" aria-selected="false">
                            <i class="fas fa-crop"></i> Crop
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-uppercase" id="rotate-tab" data-bs-toggle="tab" data-bs-target="#rotate" type="button" role="tab" aria-controls="rotate" aria-selected="false">
                            <i class="fas fa-sync-alt"></i> Rotate
                        </button>
                    </li>
                </ul>


                <!-- Tab Content -->
                <div class="tab-content" id="imageTabContent">
                    <!-- Resize Tab -->
                    <div class="tab-pane fade show active" id="resize" role="tabpanel" aria-labelledby="resize-tab">
                        <form action="resize_image.php" method="POST">
                            <input type="hidden" name="imagePath" value="<?php echo htmlspecialchars($imagePath); ?>">

                            <div class="text-center mb-4">
                                <h3>Resize Image</h3>
                                <p>Enter the new dimensions for the image below.</p>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="width" class="form-label">New Width (px):</label>
                                        <input type="number" name="width" id="width" class="form-control" placeholder="Enter width in px" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="height" class="form-label">New Height (px):</label>
                                        <input type="number" name="height" id="height" class="form-control" placeholder="Enter height in px" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Save Button -->
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success btn-lg rounded-pill py-3 px-5 shadow-md">Save Resized Image</button>
                            </div>
                        </form>
                    </div>


                    <!-- Crop Tab -->
                    <div class="tab-pane fade" id="crop" role="tabpanel" aria-labelledby="crop-tab">
                        <form id="cropForm" action="crop_image.php" method="POST">
                            <input type="hidden" name="imagePath" value="<?php echo htmlspecialchars($imagePath); ?>">
                            <input type="hidden" name="croppedImage" id="croppedImage">

                            <div class="text-center mb-4">
                                <h3>Crop Image</h3>
                                <p>Select the aspect ratio and define the crop dimensions for your image.</p>
                            </div>

                            <!-- Aspect Ratio Section -->
                            <div class="mb-4">
                                <label for="cropAspectRatio" class="form-label">Select Aspect Ratio:</label>
                                <select class="form-select" id="cropAspectRatio" name="cropAspectRatio">
                                    <option selected value="0">Freeform</option>
                                    <option value="1">Original</option>
                                    <option value="2">Custom</option>
                                    <option value="3">1:1 (Square)</option>
                                    <option value="4">4:3 (Monitor)</option>
                                    <option value="5">14:9</option>
                                    <option value="6">16:9 (Widescreen)</option>
                                    <option value="7">16:10</option>
                                    <option value="8">2:1</option>
                                    <option value="9">3:1 (Panorama)</option>
                                    <option value="10">4:1</option>
                                    <option value="11">3:2 (35mm film)</option>
                                    <option value="12">5:4</option>
                                    <option value="13">7:5</option>
                                    <option value="14">19:10</option>
                                    <option value="15">21:9 (Cinemascope)</option>
                                    <option value="16">32:9 (Super Ultra Wide)</option>
                                    <option value="17">Facebook Profile 170x170</option>
                                    <option value="18">Facebook Cover 820x312</option>
                                    <option value="19">Facebook Post 1200x900</option>
                                    <option value="20">Facebook ad 1280x720</option>
                                    <option value="21">Instagram Profile 110x110</option>
                                    <option value="22">Instagram Post 320x320</option>
                                    <option value="23">Instagram Story 1080x1920</option>
                                    <option value="24">Twitter Profile 400x400</option>
                                    <option value="25">Twitter Header 1500x1500</option>
                                    <option value="26">Twitter image 1024x512</option>
                                    <option value="27">Twitter card 1200x628</option>
                                    <option value="28">Twitter ad 1200x675</option>
                                    <option value="29">Youtube Profile 800x800</option>
                                    <option value="30">Youtube channel art 2560x1440</option>
                                    <option value="31">Youtube thumbnail 1280x720</option>
                                    <option value="32">Web mini 1024x786</option>
                                    <option value="33">Web small 1280x800</option>
                                    <option value="34">Web common 1366x768</option>
                                    <option value="35">Web medium 1440x900</option>
                                    <option value="36">Full HD 1920x1080</option>
                                    <option value="37">Ultra HD 4K 3840x2160</option>
                                    <option value="38">Paper A4 2480x3508</option>
                                    <option value="39">Paper A5 1748x2480</option>
                                    <option value="40">Paper A6 1280x1748</option>
                                    <option value="41">Paper Letter 2400x3300</option>
                                </select>
                            </div>

                            <!-- Crop Dimensions Section -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="cropWidth" class="form-label">Crop Width:</label>
                                        <input type="number" name="cropWidth" id="cropWidth" class="form-control" placeholder="Enter width in px">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="cropHeight" class="form-label">Crop Height:</label>
                                        <input type="number" name="cropHeight" id="cropHeight" class="form-control" placeholder="Enter height in px">
                                    </div>
                                </div>
                            </div>

                            <!-- Save Button -->
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success btn-lg rounded-pill py-3 px-5 shadow-md">Save Cropped Image</button>
                            </div>
                        </form>
                    </div>


                    <!-- Rotate Tab -->
                    <div class="tab-pane fade" id="rotate" role="tabpanel" aria-labelledby="rotate-tab">
                        <form id="rotateForm" action="rotate_image.php" method="POST">
                            <input type="hidden" name="action" id="action" value="">
                            <input type="hidden" name="imagePath" value="<?php echo htmlspecialchars($imagePath); ?>">

                            <!-- Section Header -->
                            <div class="text-center mb-4">
                                <h3 class="font-weight-bold text-dark">Image Rotation & Flip</h3>
                                <p class="text-muted">Select an option to rotate or flip your image.</p>
                            </div>

                            <!-- Flip Image Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h4 class="text-center mb-3 text-primary">Flip Image</h4>
                                </div>
                                <div class="col-6 col-md-6 mb-3">
                                    <a href="#" onclick="submitRotateForm('flip_horizontal')">
                                        <div class="card shadow-lg border-0 rounded-lg hover-shadow">
                                            <div class="card-body p-4 text-center">
                                                <i class="fas fa-arrows-alt-h fa-2x text-primary mb-3"></i>
                                                <h5 class="card-title text-dark">Flip Horizontally</h5>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6 col-md-6 mb-3">
                                    <a href="#" onclick="submitRotateForm('flip_vertical')">
                                        <div class="card shadow-lg border-0 rounded-lg hover-shadow">
                                            <div class="card-body p-4 text-center">
                                                <i class="fas fa-arrows-alt-v fa-2x text-primary mb-3"></i>
                                                <h5 class="card-title text-dark">Flip Vertically</h5>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <!-- Rotate Image Section -->
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="text-center mb-3 text-primary">Rotate Image</h4>
                                </div>
                                <div class="col-6 col-md-6 mb-3">
                                    <a href="#" onclick="submitRotateForm('rotate_clockwise')">
                                        <div class="card shadow-lg border-0 rounded-lg hover-shadow">
                                            <div class="card-body p-4 text-center">
                                                <i class="fas fa-sync-alt fa-2x text-primary mb-3"></i>
                                                <h5 class="card-title text-dark">Rotate Clockwise</h5>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6 col-md-6 mb-3">
                                    <a href="#" onclick="submitRotateForm('rotate_counter_clockwise')">
                                        <div class="card shadow-lg border-0 rounded-lg hover-shadow">
                                            <div class="card-body p-4 text-center">
                                                <i class="fas fa-sync-alt fa-rotate-180 fa-2x text-primary mb-3"></i>
                                                <h5 class="card-title text-dark">Rotate Counter Clockwise</h5>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <!-- Save Button -->
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success btn-lg rounded-pill py-3 px-5 shadow-md">Save Rotated Image</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js" integrity="sha512-b+nQTCdtTBIRIbraqNEwsjB6UvL3UEMkXnhzd8awtCYh0Kcsjl9uEgwVFVbhoj3uu1DO1ZMacNvLoyJJiNfcvg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="assets/js/crop.js"></script>
    <script src="assets/js/rotate.js"></script>
    <script>
        // Grab the image element and the form
        const imageToCrop = document.getElementById('imageToCrop');
        const cropPreview = document.querySelector('.img-preview');
        const rotateForm = document.getElementById('rotateForm');
        const actionInput = document.getElementById('action');

        let currentTransform = ''; // To store the current transformation state
        let currentRotation = 0; // To track the current rotation angle
        let flippedHorizontally = false; // To track the horizontal flip state
        let flippedVertically = false; // To track the vertical flip state

        // Function to apply transformation to the image preview
        function applyTransformation(transform) {
            currentTransform = transform; // Update the current transformation state
            imageToCrop.style.transform = transform; // Apply the transformation to the image
        }

        // Handle rotation or flipping
        function submitRotateForm(action) {
            actionInput.value = action; // Set the hidden action input value

            // Reset the transformation to avoid cumulative effects when flipping
            let transform = currentTransform;

            if (action === 'flip_horizontal') {
                // Toggle the horizontal flip state
                flippedHorizontally = !flippedHorizontally;
                transform = flippedHorizontally ? 'scaleX(-1)' : 'scaleX(1)';
            } else if (action === 'flip_vertical') {
                // Toggle the vertical flip state
                flippedVertically = !flippedVertically;
                transform = flippedVertically ? 'scaleY(-1)' : 'scaleY(1)';
            } else if (action === 'rotate_clockwise') {
                currentRotation += 90; // Increment rotation by 90 degrees clockwise
                transform = `rotate(${currentRotation}deg)`; // Apply the new rotation
            } else if (action === 'rotate_counter_clockwise') {
                currentRotation -= 90; // Increment rotation by 90 degrees counter-clockwise
                transform = `rotate(${currentRotation}deg)`; // Apply the new rotation
            }

            // Apply the updated transformation
            applyTransformation(transform);
        }

        // You can call this function when submitting the form (optional)
        rotateForm.addEventListener('submit', function(e) {
            // You may want to update the image to its final transformed state before submitting
            e.preventDefault();
            console.log("Saving Image with Transformation: ", currentTransform);
            // Submit the form with the final transformation state if needed
            this.submit();
        });
    </script>





</body>

</html>