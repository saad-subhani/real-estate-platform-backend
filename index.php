<?php include 'inc/header.php'; ?>
<!-- Hero Section with Search Filters -->
<div class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h1 class="text-center mb-4">Find Your Perfect Property</h1>
                <div class="card shadow">
                    <div class="card-body">
                    <?php
require_once 'backend/db.php';

// Fetch distinct locations
$locationQuery = $conn->query("SELECT DISTINCT city FROM properties WHERE city IS NOT NULL AND city != ''");
?>

<form id="propertySearchForm" method="GET" action="search-results.php">
    <div class="row g-3">
        <!-- Price Range Fields -->
        <div class="col-md-6">
            <label for="minPrice" class="form-label">Minimum Price (PKR)</label>
            <input type="number" class="form-control" id="minPrice" name="minPrice" placeholder="Enter minimum price" min="0">
        </div>

        <div class="col-md-6">
            <label for="maxPrice" class="form-label">Maximum Price (PKR)</label>
            <input type="number" class="form-control" id="maxPrice" name="maxPrice" placeholder="Enter maximum price" min="0">
        </div>

        <!-- Location Dropdown (Dynamic from DB) -->
        <div class="col-md-6">
            <label for="location" class="form-label">Location</label>
            <select class="form-select" id="location" name="location">
                <option value="">Select City</option>
                <?php while ($loc = $locationQuery->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($loc['city']); ?>">
                        <?php echo htmlspecialchars($loc['city']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Property Type Dropdown -->
        <div class="col-md-6">
            <label for="propertyType" class="form-label">Property Type</label>
            <select class="form-select" id="propertyType" name="propertyType">
                <option value="">Select Type</option>
                <option value="house">House</option>
                <option value="plot">Plot</option>
                <option value="flat">Flat</option>
                <option value="commercial">Commercial</option>
            </select>
        </div>

        <!-- Area Input -->
        <div class="col-md-6">
            <label for="area" class="form-label">Area</label>
            <div class="input-group">
                <input type="number" class="form-control" id="area" name="area" placeholder="Enter area" min="0">
                <select class="form-select" id="areaUnit" name="areaUnit" style="max-width: 130px;">
                    <option value="marla">Marla</option>
                    <option value="sqft">Square Feet</option>
                </select>
            </div>
        </div>

        <!-- Search Button -->
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary btn-lg px-5" id="searchButton">
                Search Properties
            </button>
        </div>
    </div>
</form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Properties and Recent Listings Section -->
<div class="properties-section py-5">
    <div class="container">

        <div class="row mb-5" id="latestPropertySection">
            <!-- Content will be injected here -->
        </div>
    </div>
</div>

<!-- Testimonials and Verified Badge Section -->
<div class="testimonials-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <!-- Testimonials Carousel -->
            <div class="col-lg-8 mb-4 mb-lg-0">
                <h2 class="section-title mb-4">What Our Clients Say</h2>
                <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <!-- Testimonial 1 -->
                        <div class="carousel-item active">
                            <div class="testimonial-card">
                                <div class="row align-items-center">
                                    <div class="col-md-4 text-center">
                                        <div class="testimonial-image">
                                            <img src="images/testimonial1.jpg" alt="Client 1" class="rounded-circle">
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="testimonial-content">
                                            <p class="testimonial-text">"PropFind made my property search incredibly easy. Their verified listings gave me confidence in my decision. I found my dream home within weeks!"</p>
                                            <h5 class="testimonial-name">Ayesha Malik</h5>
                                            <p class="testimonial-location">Islamabad</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Testimonial 2 -->
                        <div class="carousel-item">
                            <div class="testimonial-card">
                                <div class="row align-items-center">
                                    <div class="col-md-4 text-center">
                                        <div class="testimonial-image">
                                            <img src="images/testimonial2.jpg" alt="Client 2" class="rounded-circle">
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="testimonial-content">
                                            <p class="testimonial-text">"The verified badge system helped me identify trustworthy properties. The whole process was transparent and professional."</p>
                                            <h5 class="testimonial-name">Sara Ali</h5>
                                            <p class="testimonial-location">Lahore</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Testimonial 3 -->
                        <div class="carousel-item">
                            <div class="testimonial-card">
                                <div class="row align-items-center">
                                    <div class="col-md-4 text-center">
                                        <div class="testimonial-image">
                                            <img src="images/testimonial3.jpg" alt="Client 3" class="rounded-circle">
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="testimonial-content">
                                            <p class="testimonial-text">"As a first-time buyer, PropFind's verification system gave me peace of mind. Their platform is user-friendly and reliable."</p>
                                            <h5 class="testimonial-name">Fatima Ahmed</h5>
                                            <p class="testimonial-location">Karachi</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Carousel Controls -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                    <!-- Carousel Indicators -->
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    </div>
                </div>
            </div>

            <!-- Verified Badge Info -->
            <div class="col-lg-4">
                <div class="verified-info-card">
                    <h2 class="section-title mb-4">Verified Properties</h2>
                    <div class="verified-badge-large mb-4">
                        <i class="fas fa-check-circle"></i>
                        <span>Verified</span>
                    </div>
                    <h5 class="mb-3">What does verification mean?</h5>
                    <ul class="verified-features">
                        <li>
                            <i class="fas fa-check text-success"></i>
                            Property physically inspected by our team
                        </li>
                        <li>
                            <i class="fas fa-check text-success"></i>
                            Documents verified for authenticity
                        </li>
                        <li>
                            <i class="fas fa-check text-success"></i>
                            Accurate property measurements
                        </li>
                        <li>
                            <i class="fas fa-check text-success"></i>
                            Direct contact with verified owners
                        </li>
                    </ul>
                    <p class="mt-3">Look for this badge on our listings to ensure you're viewing verified properties that meet our quality standards.</p>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="js/main.js"></script>
<script src="js/notifications.js"></script>

<div class="toast-container position-fixed top-0 end-0 p-3"></div>

<?php include'inc/footer.php';?>