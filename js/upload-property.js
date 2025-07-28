
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('propertyUploadForm');
    const previewBtn = document.getElementById('previewBtn');
    const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
    const confirmSubmitBtn = document.getElementById('confirmSubmit');
    const propertyImagesInput = document.getElementById('propertyImages');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const maxImages = 5;

    // Helper to format field labels
    function formatLabel(key) {
        return key
            .replace(/([A-Z])/g, ' $1')
            .replace(/^./, str => str.toUpperCase())
            .replace(/([a-z])(\d)/gi, '$1 $2');
    }

    // Show thumbnails below file input
    propertyImagesInput.addEventListener('change', function (e) {
        imagePreviewContainer.innerHTML = ''; // Clear old previews
        const files = Array.from(this.files);

        if (files.length > maxImages) {
            alert(`You can upload up to ${maxImages} images.`);
            this.value = ''; // Reset input
            return;
        }

        files.forEach(file => {
            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'img-thumbnail me-2 mb-2';
                img.style.height = '100px';
                imagePreviewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });

    // Preview modal logic
    previewBtn.addEventListener('click', function () {
        const formData = new FormData(form);
        let previewHTML = '';
    
        for (const [key, value] of formData.entries()) {
            if (key === 'propertyImages[]' || key === 'cnicImage' || key === 'ownershipDocs') {
                const files = key === 'propertyImages[]'
                    ? Array.from(formData.getAll(key)).map(f => f.name).join(', ')
                    : value.name;
    
                previewHTML += `
                    <div class="mb-2">
                        <strong>${formatLabel(key)}:</strong> ${files}
                    </div>`;
            } else if (key === 'description') {
                // Skip 'description' from FormData to avoid raw textarea content
                continue;
            } else {
                previewHTML += `
                    <div class="mb-2">
                        <strong>${formatLabel(key)}:</strong> ${value}
                    </div>`;
            }
        }
    
        // Add CKEditor (description) content manually
        if (descriptionEditor) {
            previewHTML += `
                <div class="mb-2">
                    <strong>Description:</strong> ${descriptionEditor.getData()}
                </div>`;
        }
    
        document.getElementById('previewContent').innerHTML = previewHTML;
        previewModal.show();
    });
    

    // Optional: clear previews after confirm
    confirmSubmitBtn.addEventListener('click', function () {
        previewModal.hide();
        imagePreviewContainer.innerHTML = ''; // Clear thumbnails if needed
    });
});

