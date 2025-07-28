<?php include 'inc/header.php'; ?>
<?php
include 'backend/db.php';

$propertyId = $_GET['id'] ?? 0;
if (!$propertyId) {
    die('Property ID is missing');
}

$query = "SELECT p.user_id, p.title, p.price, p.area , p.type, p.location, p.unit, p.images_json, p.created_at, p.description, p.link, u.picture,u.name AS user_name 
          FROM properties p
          JOIN users u ON p.user_id = u.id
          WHERE p.id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $propertyId);
$stmt->execute();
$result = $stmt->get_result();
$property = $result->fetch_assoc();

if (!$property) {
    die('Property not found');
}

$images = json_decode($property['images_json'], true);
?>

<!-- Main Content -->
<main class="property-detail-container mt-5 pt-4">
    <div class="container">
        <!-- Property Header -->
        <div class="property-header mb-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="property-title"><?php echo $property['title'] ?></h1>
                    <p class="property-location">
                        <i class="fas fa-map-marker-alt" style="margin-right:10px;"></i><?php echo $property['location'] ?>
                        <span class="verified-badge ms-2">
                            <i class="fas fa-check-circle"></i> Verified
                        </span>
                    </p>
                </div>
                <div class="property-actions">
                    <button class="btn btn-outline-primary me-2" id="bookmarkBtn" data-bs-toggle="tooltip" title="Save Property">
                        <i class="far fa-bookmark"></i> Save
                    </button>
                    <button class="btn btn-outline-danger" id="reportBtn" data-bs-toggle="tooltip" title="Report Property">
                        <i class="fas fa-flag"></i> Report
                    </button>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-8">
                <!-- Image Gallery -->
                <?php
                $images = json_decode($property['images_json'], true);
                ?>

                <div class="property-gallery card mb-4">
                    <div class="swiper">
                        <div class="swiper-wrapper">
                            <?php if (!empty($images) && is_array($images)): ?>
                                <?php foreach ($images as $index => $image): ?>
                                    <div class="swiper-slide">
                                        <img src="<?php echo htmlspecialchars($image); ?>" alt="Property Image <?php echo $index + 1; ?>">
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="swiper-slide">
                                    <img src="https://placehold.co/800x600/png" alt="No Property Images">
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                </div>


                <!-- Video Tour -->
                <div class="property-video card mb-4" id="videoTourSection">
                    <div class="card-body">
                        <h3 class="card-title">Video Tour</h3>
                        <div class="ratio ratio-16x9">
                            <iframe src="https://www.youtube.com/embed/your-video-id"
                                title="Property Video Tour"
                                allowfullscreen></iframe>
                        </div>
                    </div>
                </div>

                <!-- Property Details -->
                <div class="property-details card mb-4">
                    <div class="card-body">
                        <h3 class="card-title">Property Details</h3>
                        <div class="row g-3">
                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span class="label">Price:</span>
                                    <span class="value"><?php echo  'Pkr' . ' ' .  $property['price'] ?></span>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <i class="fas fa-ruler-combined"></i>
                                    <span class="label">Area:</span>
                                    <span class="value"><?php echo $property['area'] . ' ' .  $property['unit'] ?></span>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <i class="fas fa-home"></i>
                                    <span class="label">Type:</span>
                                    <span class="value"><?php echo $property['type'] ?></span>
                                </div>
                            </div>
                            <div class="col-sm-8 col-md-6">
                                <div class="detail-item">
                                    <i class="fas fa-bed"></i>
                                    <span class="label">Posted By:</span>
                                    <span class="value"><?php echo $property['user_name'] ?></span>
                                </div>
                            </div>
                            <div class="col-sm-8 col-md-6">
                                <div class="detail-item">
                                    <i class="fas fa-bath"></i>
                                    <span class="label">Posted on:</span>
                                    <span class="value">
                                        <?php echo date('Y-m-d', strtotime($property['created_at'])); ?>
                                    </span>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="property-description card mb-4">
                    <div class="card-body">
                        <h3 class="card-title">Description</h3>
                        <?php echo $property['description'] ?>
                    </div>
                </div>

                <!-- Location -->
                <div class="property-location card mb-4">
                    <div class="card-body">
                        <h3 class="card-title">Location</h3>
                        <div class="ratio ratio-16x9">
                            <?php echo $property['link']; ?>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Contact Form -->
                <div class="contact-form card sticky-top">
                    <div class="card-body">
                        <h3 class="card-title">Contact Agent</h3>
                        <div class="agent-info mb-3">
                            <img src="<?php echo !empty($property['picture']) ? htmlspecialchars($property['picture']) : 'https://placehold.co/100x100'; ?>"
                                alt="Agent"
                                class="agent-avatar">

                            <div class="agent-details">
                                <h5 class="agent-name"><?php echo $property['user_name'] ?></h5>
                                <p class="agent-title"> Property Consultant</p>
                            </div>
                        </div>
                        <form id="contactForm" novalidate>
                            <div class="mb-3">
                                <label for="name" class="form-label">Your Name</label>
                                <input type="text" class="form-control" id="name" required>
                                <div class="invalid-feedback">
                                    Please enter your name
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" required>
                                <div class="invalid-feedback">
                                    Please enter a valid email address
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" required>
                                <div class="invalid-feedback">
                                    Please enter your phone number
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" rows="4" required></textarea>
                                <div class="invalid-feedback">
                                    Please enter your message
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-paper-plane me-2"></i>Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<!-- Custom JS -->
<script src="js/property-detail.js"></script>
<?php 'inc/footer.php'?>