// Initialize Cropper.js (only when Crop tab is active)
var image = document.getElementById('imageToCrop');
var cropper;

// Default aspect ratio (16:9)
var aspectRatio = 16 / 9;

// Set the width and height for the selected aspect ratio
var aspectRatios = {
    0: null,
    1: null,
    2: null,
    3: 1 / 1,
    4: 4 / 3,
    5: 14 / 9,
    6: 16 / 9,
    7: 16 / 10,
    8: 2 / 1,
    9: 3 / 1,
    10: 4 / 1,
    11: 3 / 2,
    12: 5 / 4,
    13: 7 / 5,
    14: 19 / 10,
    15: 21 / 9,
    16: 32 / 9,
    17: 170 / 170,
    18: 820 / 312,
    19: 1200 / 900,
    20: 1280 / 720,
    21: 110 / 110,
    22: 320 / 320,
    23: 1080 / 1920,
    24: 400 / 400,
    25: 1500 / 1500,
    26: 1024 / 512,
    27: 1200 / 628,
    28: 1200 / 675,
    29: 800 / 800,
    30: 2560 / 1440,
    31: 1280 / 720,
    32: 1024 / 786,
    33: 1280 / 800,
    34: 1366 / 768,
    35: 140 / 900,
    36: 1920 / 1080,
    37: 3840 / 2160,
    38: 2480 / 3508,
    39: 1748 / 2480,
    40: 1280 / 1748,
    41: 2400 / 3300
};

// Activate the cropper only when the Crop tab is selected
document.getElementById('crop-tab').addEventListener('shown.bs.tab', function () {
    cropper = new Cropper(image, {
        aspectRatio: aspectRatio,
        viewMode: 1,
        preview: '.img-preview',
        autoCropArea: 0.5,
        responsive: true,
        crop: function (event) {
            var canvas = cropper.getCroppedCanvas();
            // Display the cropped image data as base64
            document.getElementById('croppedImage').value = canvas.toDataURL();
        }
    });
});

// Deactivate the cropper when switching away from the Crop tab
document.getElementById('resize-tab').addEventListener('shown.bs.tab', function () {
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
});
document.getElementById('rotate-tab').addEventListener('shown.bs.tab', function () {
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
});

// Update the cropper's aspect ratio and show the new width and height when the user selects an aspect ratio
document.getElementById('cropAspectRatio').addEventListener('change', function () {
    var selectedValue = this.value;
    var newAspectRatio = aspectRatios[selectedValue];

    if (newAspectRatio !== null) {
        aspectRatio = newAspectRatio;
        // Update the cropper with the new aspect ratio
        cropper.setAspectRatio(aspectRatio);
    }

    // Update the width and height inputs based on the selected aspect ratio
    updateWidthHeightInputs();
});

// Function to update the width and height inputs based on the selected aspect ratio
function updateWidthHeightInputs() {
    var selectedValue = document.getElementById('cropAspectRatio').value;

    var widthInput = document.getElementById('cropWidth');
    var heightInput = document.getElementById('cropHeight');

    if (selectedValue === "0" || selectedValue === "2") {
        // Freeform or custom, let the user enter their own values
        widthInput.disabled = false;
        heightInput.disabled = false;
        widthInput.value = '';
        heightInput.value = '';
    } else {
        // Set the width and height according to the selected aspect ratio
        var ratio = aspectRatios[selectedValue];
        if (ratio) {
            var width = 500; // Default width (could be dynamic based on image size)
            var height = width / ratio;

            widthInput.value = Math.round(width);
            heightInput.value = Math.round(height);

            widthInput.disabled = true;
            heightInput.disabled = true;
        }
    }
}

// Initial call to set the width and height inputs on page load
updateWidthHeightInputs();

// Handle crop form submission
document.getElementById('cropForm').onsubmit = function (event) {
    event.preventDefault();
    var croppedImage = document.getElementById('croppedImage').value;
    if (!croppedImage) {
        alert("Please crop the image before submitting.");
        return false;
    }
    this.submit();
};
