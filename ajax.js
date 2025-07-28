document.addEventListener('DOMContentLoaded', function () {
    console.log("✅ DOM fully loaded. AJAX script running.");

    // Toggle Password Visibility
    document.getElementById('togglePassword')?.addEventListener('click', function () {
        const input = document.getElementById('password');
        input.type = input.type === 'password' ? 'text' : 'password';
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    document.getElementById('toggleConfirmPassword')?.addEventListener('click', function () {
        const input = document.getElementById('confirmPassword');
        input.type = input.type === 'password' ? 'text' : 'password';
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    // Handle AJAX form submissions
    const ajaxForms = document.querySelectorAll('form.ajax-form, form.ajax-img');
    console.log(`📝 Found ${ajaxForms.length} forms with AJAX classes.`);

    ajaxForms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            console.log("📨 Form submission intercepted.");

            const action = this.dataset.action;
            if (!action) {
                console.warn("⚠️ data-action attribute is missing.");
                return;
            }

            const isImageForm = this.classList.contains('ajax-img');
            console.log(`🧾 Action: ${action}, Is Image Form: ${isImageForm}`);

            // Validation
            if (!this.checkValidity()) {
                console.warn("❌ Form validation failed.");
                this.classList.add('was-validated');
                return;
            }

            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerText = 'Processing...';
            }

            let data;
            if (isImageForm) {
                data = new FormData(this);
                data.append('action', action);
                console.log("📦 Using FormData for image form.");
            } else {
                const formData = new FormData(this);
                formData.append('action', action);
                data = new URLSearchParams(formData).toString();
                console.log("📦 Using URLSearchParams for normal form.");
            }

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'api.php', true);
            if (!isImageForm) {
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            }

            xhr.onload = () => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Sign Up';
                }

                console.log("📬 Response received from server:", xhr.responseText);

                try {
                    const res = JSON.parse(xhr.responseText);
                    if (res.status === 'success') {
                        console.log("✅ Success:", res.message);
                        iziToast.success({
                            title: 'Success',
                            message: res.message || 'Operation completed successfully',
                            position: 'topRight'
                        });

                        // Redirect if login
                        if (action === 'login_user') {
                            setTimeout(() => {
                                window.location.href = 'dashboard/dashboard.php';
                            }, 1500); // slight delay so user sees success
                        } else {
                            form.reset();
                            form.classList.remove('was-validated');
                        }

                    } else {
                        console.warn("⚠️ Server responded with error:", res.message);
                        iziToast.error({
                            title: 'Error',
                            message: res.message || 'Something went wrong',
                            position: 'topRight'
                        });
                    }
                } catch (err) {
                    console.error("❌ Failed to parse JSON:", err);
                    iziToast.error({
                        title: 'Error',
                        message: 'Invalid server response',
                        position: 'topRight'
                    });
                }
            };

            xhr.onerror = () => {
                console.error("❌ AJAX request failed.");
                iziToast.error({
                    title: 'Error',
                    message: 'Request failed. Please try again.',
                    position: 'topRight'
                });
            };

            xhr.send(data);
            console.log("📤 AJAX request sent to api.php");
        });
    });
});
