<?php include'inc/header.php'; ?>
    <!-- Main Content -->
    <main class="contact-container">
        <!-- Contact Header -->
        <section class="contact-header text-center">
            <div class="container">
                <h1 class="display-4 fw-bold text-primary mb-2">Contact Us</h1>
                <p class="lead text-muted">We're here to help with all your real estate needs</p>
            </div>
        </section>

        <!-- Contact Content -->
        <section class="contact-content py-5">
            <div class="container">
                <div class="row g-5">
                    <!-- Contact Form Column -->
                    <div class="col-lg-6">
                        <div class="contact-form-wrapper">
                            <h2 class="h3 mb-4">Send us a message</h2>
                            <form id="contactForm" class="contact-form">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="name" placeholder="Your Name" required>
                                    <label for="name">Your Name</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" placeholder="Email Address" required>
                                    <label for="email">Email Address</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="message" placeholder="Your Message" style="height: 150px" required></textarea>
                                    <label for="message">Your Message</label>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-paper-plane me-2"></i>Send Message
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Contact Info Column -->
                    <div class="col-lg-6">
                        <div class="contact-info-wrapper">
                            <h2 class="h3 mb-4">Get in touch</h2>
                            
                            <!-- Contact Cards -->
                            <div class="contact-card mb-4">
                                <div class="icon-wrapper">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="info-content">
                                    <h3>Our Location</h3>
                                    <p>Government college university,<br>Faisalabad, Pakistan</p>
                                </div>
                            </div>

                            <div class="contact-card mb-4">
                                <div class="icon-wrapper">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="info-content">
                                    <h3>Phone Number</h3>
                                    <p>+92 3245947204</p>
                                </div>
                            </div>

                            <div class="contact-card mb-4">
                                <div class="icon-wrapper">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="info-content">
                                    <h3>Email Address</h3>
                                    <p>ammaramjad0324@gmail.com</p>
                                </div>
                            </div>

                            <!-- Map -->
                            <div class="map-wrapper mt-5">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3318.8755993177407!2d73.08400931513811!3d33.71439148070458!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x38dfbef8c1c9679b%3A0x6849f36a1e0f6d9d!2sIslamabad%2C%20Pakistan!5e0!3m2!1sen!2s!4v1645000000000!5m2!1sen!2s" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>



    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JavaScript -->
    <script src="js/main.js"></script>
    <?php include'inc/footer.php'?>