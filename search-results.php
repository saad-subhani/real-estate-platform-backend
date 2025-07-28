<?php
include 'inc/header.php';
require_once 'backend/db.php';

// Initialize filters from GET
$minPrice = $_GET['minPrice'] ?? null;
$maxPrice = $_GET['maxPrice'] ?? null;
$location = $_GET['location'] ?? null;
$propertyType = $_GET['propertyType'] ?? null;
$area = $_GET['area'] ?? null;
$areaUnit = $_GET['areaUnit'] ?? null;

// Base query
$query = "SELECT * FROM properties WHERE 1=1";
$params = [];
$types = '';

// Add filters dynamically
if (!empty($minPrice)) {
    $query .= " AND price >= ?";
    $params[] = $minPrice;
    $types .= 'd';
}

if (!empty($maxPrice)) {
    $query .= " AND price <= ?";
    $params[] = $maxPrice;
    $types .= 'd';
}

if (!empty($location)) {
    $query .= " AND city = ?";
    $params[] = $location;
    $types .= 's';
}

if (!empty($propertyType)) {
    $query .= " AND type = ?";
    $params[] = $propertyType;
    $types .= 's';
}

if (!empty($area)) {
    $query .= " AND area = ?";
    $params[] = $area;
    $types .= 'd';
}

if (!empty($areaUnit)) {
    $query .= " AND unit = ?";
    $params[] = $areaUnit;
    $types .= 's';
}

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$properties = [];
while ($row = $result->fetch_assoc()) {
    $properties[] = $row;
}

$stmt->close();
?>

<div class="container-fluid py-4">
    <h3 class="mb-4"><?php echo count($properties); ?> Search Results Found</h3>

    <div class="row g-4">
        <?php if (empty($properties)): ?>
            <div class="alert alert-warning text-center">No properties found matching your criteria.</div>
        <?php else: ?>
            <?php foreach ($properties as $property): ?>
                <?php
                    $images = json_decode($property['images_json'], true);
                    $firstImage = (!empty($images) && isset($images[0])) ? htmlspecialchars($images[0]) : 'https://via.placeholder.com/300x200?text=No+Image';
                    $bookmarkIcon = !empty($property['is_saved']) ? 'fas fa-bookmark text-primary' : 'far fa-bookmark';
                ?>
                <div class="col-md-4">
                    <div class="property-card card h-100 shadow-sm border-0 rounded-4">
                        <div class="property-image-wrapper position-relative">
                            <img src="<?php echo $firstImage; ?>" alt="Property Image" class="card-img-top rounded-top-4" style="height: 200px; object-fit: cover;">
                            
                            <span class="verified-badge badge bg-success position-absolute top-0 start-0 m-2"><i class="fas fa-check-circle"></i> Verified</span>

                            <button class="favorite-btn position-absolute top-0 end-0 m-2 btn btn-light rounded-circle shadow-sm"
                                data-property-id="<?php echo $property['id']; ?>"
                                data-owner-id="<?php echo $property['user_id']; ?>"
                                title="Save Property">
                                <i class="<?php echo $bookmarkIcon; ?>"></i>
                            </button>
                        </div>

                        <a href="view-property-detail.php?id=<?php echo $property['id']; ?>" class="text-decoration-none text-dark">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($property['title']); ?></h5>
                                <p class="card-text text-primary fw-bold">PKR <?php echo number_format($property['price']); ?></p>
                                <p class="card-text"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($property['location']); ?></p>
                                <div class="property-features d-flex flex-wrap gap-3 mt-2">
                                    <?php if (!empty($property['bedrooms'])): ?>
                                        <span><i class="fas fa-bed"></i> <?php echo $property['bedrooms']; ?> Beds</span>
                                    <?php endif; ?>
                                    <?php if (!empty($property['bathrooms'])): ?>
                                        <span><i class="fas fa-bath"></i> <?php echo $property['bathrooms']; ?> Baths</span>
                                    <?php endif; ?>
                                    <?php if (!empty($property['area'])): ?>
                                        <span><i class="fas fa-ruler-combined"></i> <?php echo $property['area'] . ' ' . htmlspecialchars($property['unit']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    document.querySelectorAll('.favorite-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const propertyId = this.getAttribute('data-property-id');
        const ownerId = this.getAttribute('data-owner-id');
        const icon = this.querySelector('i');

        fetch('backend/check-session.php')
            .then(res => res.json())
            .then(sessionData => {
                if (!sessionData.logged_in) {
                    iziToast.warning({
                        title: 'Login Required',
                        message: 'You need to be logged in to save this property.',
                        position: 'topRight'
                    });
                    return;
                }

                if (sessionData.user_id == ownerId) {
                    iziToast.error({
                        title: 'Not Allowed',
                        message: 'You cannot save your own property.',
                        position: 'topRight'
                    });
                    return;
                }

                fetch('backend/save-property.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ property_id: propertyId })
                })
                .then(res => res.json())
                .then(saveData => {
                    if (saveData.status === 'success') {
                        if (saveData.is_saved === 1) {
                            icon.classList.remove('far');
                            icon.classList.add('fas', 'text-primary');
                            iziToast.success({
                                title: 'Saved',
                                message: 'Property added to favorites.',
                                position: 'topRight'
                            });
                        } else {
                            icon.classList.remove('fas', 'text-primary');
                            icon.classList.add('far');
                            iziToast.info({
                                title: 'Removed',
                                message: 'Property removed from favorites.',
                                position: 'topRight'
                            });
                        }
                    } else {
                        iziToast.error({
                            title: 'Error',
                            message: saveData.message || 'Failed to save property.',
                            position: 'topRight'
                        });
                    }
                });
            })
            .catch(err => {
                console.error('Session check failed:', err);
                iziToast.error({
                    title: 'Error',
                    message: 'Failed to check session.',
                    position: 'topRight'
                });
            });
    });
});

</script>


<?php include'inc/footer.php'?>