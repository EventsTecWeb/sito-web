document.addEventListener("DOMContentLoaded", function() {
    var imageContainer = document.getElementById("image-container");
    var imageInput = document.getElementById("image");
    var imagePreview = document.getElementById("image-preview");
    var dragDropText = document.querySelector(".drag-drop-text");

    // Allow drop events on the image container
    imageContainer.addEventListener("dragover", function(e) {
        e.preventDefault();
        imageContainer.classList.add("drag-over");
    });

    imageContainer.addEventListener("dragleave", function() {
        imageContainer.classList.remove("drag-over");
    });

    imageContainer.addEventListener("drop", function(e) {
        e.preventDefault();
        imageContainer.classList.remove("drag-over");

        var droppedFiles = e.dataTransfer.files;
        handleFiles(droppedFiles);
    });

    // Allow change event on the image input
    imageInput.addEventListener("change", function() {
        var selectedFiles = imageInput.files;
        handleFiles(selectedFiles);
    });

    function handleFiles(files) {
        if (files.length > 0) {
            var selectedFile = files[0];

            // Display the image preview
            var reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.visibility = "visible"; // Mostra l'immagine
                dragDropText.style.display = "none"; // Nasconde il testo
            };
            reader.readAsDataURL(selectedFile);
        }
    }
});