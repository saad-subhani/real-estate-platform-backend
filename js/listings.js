// Global variables
let currentView = 'grid';
let currentPage = 1;
const itemsPerPage = 9;
let properties = [];

// DOM Elements
const propertiesGrid = document.getElementById('propertiesGrid');
const gridViewBtn = document.getElementById('gridView');
const listViewBtn = document.getElementById('listView');
const filterForm = document.getElementById('filterForm');
const pagination = document.getElementById('pagination');

// Fetch properties from backend
const fetchProperties = async () => {
    const formData = new FormData(filterForm);
    const res = await fetch('backend/fetch-all-properties.php', {
        method: 'POST',
        body: formData
    });
    const data = await res.json();
    if (data.status === 'success') {
        const savedProperties = JSON.parse(localStorage.getItem('savedProperties')) || [];
        properties = data.properties.map(p => ({
            ...p,
            image: p.images && p.images.length > 0 ? p.images[0] : 'placeholder.jpg',
            isSaved: savedProperties.some(sp => sp.id === p.id)
        }));
        renderProperties();
    }
};

function createGridCard(property) {
    return `
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card property-card h-100" data-property-id="${property.id}">
                <div class="property-image-wrapper position-relative">
                    <img src="${property.image}" class="card-img-top" alt="${property.title}">
                    ${property.verified ? '<span class="verified-badge"><i class="fas fa-check-circle"></i> Verified</span>' : ''}
                    <button class="bookmark-btn position-absolute top-0 end-0 m-2 btn btn-light btn-sm" title="${property.isSaved ? 'Remove from Saved' : 'Save Property'}">
                        <i class="fa${property.isSaved ? 's' : 'r'} fa-bookmark text-primary"></i>
                    </button>
                </div>
                <div class="card-body">
                    <h5 class="card-title">${property.title}</h5>
                    <p class="card-text text-primary fw-bold">PKR ${parseInt(property.price).toLocaleString()}</p>
                    <p class="card-text"><i class="fas fa-map-marker-alt"></i> ${property.location}</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <a href="view-property-detail.php?id=${property.id}" class="btn btn-primary btn-view-details-sm">View Details</a>
                        <div class="property-features">
                            ${property.bedrooms ? `<span><i class="fas fa-bed"></i> ${property.bedrooms}</span>` : ''}
                            ${property.bathrooms ? `<span><i class="fas fa-bath"></i> ${property.bathrooms}</span>` : ''}
                            ${property.size ? `<span><i class="fas fa-ruler-combined"></i> ${property.size}</span>` : ''}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function createListCard(property) {
    return `
    <div class="col-12 mb-4">
        <a href="view-view-property-detail.php?id=${property.id}" class="text-decoration-none text-dark">
            <div class="card property-card list-view" data-property-id="${property.id}">
                <div class="row g-0">
                    <div class="col-md-4 position-relative">
                        <img src="${property.image}" class="card-img-top h-100" alt="${property.title}">
                        ${property.verified ? '<span class="verified-badge"><i class="fas fa-check-circle"></i> Verified</span>' : ''}
                        <button class="bookmark-btn position-absolute top-0 end-0 m-2 btn btn-light btn-sm" title="${property.isSaved ? 'Remove from Saved' : 'Save Property'}">
                            <i class="fa${property.isSaved ? 's' : 'r'} fa-bookmark text-primary"></i>
                        </button>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body d-flex flex-column h-100">
                            <div class="mb-auto">
                                <h5 class="card-title">${property.title}</h5>
                                <p class="card-text text-primary fw-bold">PKR ${parseInt(property.price).toLocaleString()}</p>
                                <p class="card-text"><i class="fas fa-map-marker-alt"></i> ${property.location}</p>
                                <div class="property-features mb-3">
                                    ${property.bedrooms ? `<span><i class="fas fa-bed"></i> ${property.bedrooms} Beds</span>` : ''}
                                    ${property.bathrooms ? `<span><i class="fas fa-bath"></i> ${property.bathrooms} Baths</span>` : ''}
                                    ${property.size ? `<span><i class="fas fa-ruler-combined"></i> ${property.size} sq ft</span>` : ''}
                                </div>
                            </div>
                            <span class="btn btn-primary align-self-start">View Details</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
`;

}

async function handleBookmarkClick(btn, property) {
    const icon = btn.querySelector('i');
    const isSaved = icon.classList.contains('fas'); // true if currently saved
    const url = 'backend/save-property.php';
    
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ property_id: property.id, action: isSaved ? 'remove' : 'add' })

        });

        const data = await response.json();
        if (data.status === 'success') {
            let savedProperties = JSON.parse(localStorage.getItem('savedProperties')) || [];

            if (isSaved) {
                // Removing from saved
                savedProperties = savedProperties.filter(p => p.id !== property.id);
                iziToast.info({
                    title: 'Removed',
                    message: 'Property removed from saved.',
                    position: 'topRight'
                });
            } else {
                // Adding to saved
                savedProperties.push(property);
                iziToast.success({
                    title: 'Saved',
                    message: 'Property saved successfully.',
                    position: 'topRight'
                });
            }

            localStorage.setItem('savedProperties', JSON.stringify(savedProperties));

            // Toggle icon
            icon.classList.toggle('fas', !isSaved);
            icon.classList.toggle('far', isSaved);
        } else {
            iziToast.error({
                title: 'Error',
                message: data.message || 'Failed to update saved property.',
                position: 'topRight'
            });
        }

    } catch (err) {
        console.error('Failed to toggle save:', err);
        iziToast.error({
            title: 'Error',
            message: 'An error occurred while saving the property.',
            position: 'topRight'
        });
    }
}

function renderProperties(filtered = properties) {
    const start = (currentPage - 1) * itemsPerPage;
    const paginated = filtered.slice(start, start + itemsPerPage);

    if (paginated.length === 0) {
        propertiesGrid.innerHTML = `
            <div class="col-12">
                <p class="text-center text-muted">No listings found.</p>
            </div>
        `;
        pagination.innerHTML = '';
        return;
    }

    propertiesGrid.innerHTML = paginated
        .map(p => currentView === 'grid' ? createGridCard(p) : createListCard(p))
        .join('');

    document.querySelectorAll('.bookmark-btn').forEach(btn => {
        const id = btn.closest('.property-card').dataset.propertyId;
        const prop = properties.find(p => p.id == id);
        btn.addEventListener('click', () => handleBookmarkClick(btn, prop));
    });

    updatePagination(filtered.length);
}

function updatePagination(totalItems) {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    let html = '';
    html += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a></li>`;
    for (let i = 1; i <= totalPages; i++) {
        html += `<li class="page-item ${i === currentPage ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
    }
    html += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${currentPage + 1}">Next</a></li>`;
    pagination.innerHTML = html;
}

pagination.addEventListener('click', function (e) {
    e.preventDefault();
    const page = parseInt(e.target.dataset.page);
    if (!isNaN(page)) {
        currentPage = page;
        renderProperties();
    }
});

document.addEventListener('DOMContentLoaded', async () => {
    await fetchProperties();

    gridViewBtn.addEventListener('click', () => {
        currentView = 'grid';
        gridViewBtn.classList.add('active');
        listViewBtn.classList.remove('active');
        renderProperties();
    });

    listViewBtn.addEventListener('click', () => {
        currentView = 'list';
        listViewBtn.classList.add('active');
        gridViewBtn.classList.remove('active');
        renderProperties();
    });

    filterForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        currentPage = 1;
        await fetchProperties();
    });
});
