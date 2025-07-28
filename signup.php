<?php include'inc/header.php';?>

    <!-- Main Content -->
    <main class="auth-container mt-5 pt-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-body p-5">
                            <h2 class="text-center mb-4">Create Account</h2>
                            <form id="signupForm" class="ajax-img" data-action="signup_user" enctype="multipart/form-data">
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="col-md-6">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="+92 300 1234567" required>
        </div>
        <div class="col-md-6">
            <label for="location" class="form-label">Location / Address</label>
            <input type="text" class="form-control" id="location" name="location" placeholder="City, Country" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="cnic" class="form-label">CNIC (XXXXX-XXXXXXX-X)</label>
            <input type="text" class="form-control" id="cnic" name="cnic" placeholder="12345-1234567-1" required>
        </div>
        <div class="col-md-6">
            <label for="role" class="form-label">Account Type</label>
            <select class="form-select" id="role" name="role" required>
                <option value="">Select account type</option>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="referralCode" class="form-label">Referral Code (Optional)</label>
            <input type="text" class="form-control" id="referralCode" name="referralCode" placeholder="Enter referral code if any">
            <small class="text-muted">The referrer will get bonus points if this is provided.</small>
        </div>
        <div class="col-md-6">
            <label for="picture" class="form-label">Profile Picture (Optional)</label>
            <input type="file" class="form-control" id="picture" name="picture" accept="image/*">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
        <div class="col-md-6">
            <label for="confirmPassword" class="form-label">Confirm Password</label>
            <div class="input-group">
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 mb-3">Sign Up</button>
    <p class="text-center mb-0">
        Already have an account? <a href="login.php">Log In</a>
    </p>
</form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    
    <script src="ajax.js"></script>
    
</body>
</html> 