// DOM Elements
const contactForm = document.getElementById('contactForm');

// Handle form submission
contactForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    // Get form data
    const formData = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        subject: document.getElementById('subject').value,
        message: document.getElementById('message').value
    };

    // Get submit button
    const submitBtn = contactForm.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;

    try {
        // Show loading state
        submitBtn.classList.add('btn-loading');
        submitBtn.innerHTML = 'Sending...';

        // Simulate API call with timeout
        // Replace this with actual API call in production
        await new Promise(resolve => setTimeout(resolve, 1500));

        // Show success message
        showAlert('success', 'Thank you! Your message has been sent successfully.');
        
        // Reset form
        contactForm.reset();

    } catch (error) {
        console.error('Error sending message:', error);
        showAlert('danger', 'Sorry, there was an error sending your message. Please try again later.');
    } finally {
        // Reset button state
        submitBtn.classList.remove('btn-loading');
        submitBtn.innerHTML = originalBtnText;
    }
});

// Show alert message
function showAlert(type, message) {
    // Remove any existing alerts
    const existingAlert = document.querySelector('.alert');
    if (existingAlert) {
        existingAlert.remove();
    }

    // Create new alert
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show mt-3`;
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    // Insert alert before form
    contactForm.parentElement.insertBefore(alert, contactForm);

    // Auto dismiss after 5 seconds
    setTimeout(() => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    }, 5000);
}

// Form validation
function validateForm() {
    const inputs = contactForm.querySelectorAll('input, textarea');
    let isValid = true;

    inputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
        }

        // Email validation
        if (input.type === 'email' && !validateEmail(input.value)) {
            isValid = false;
            input.classList.add('is-invalid');
        }
    });

    return isValid;
}

// Email validation helper
function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

// Add input event listeners for real-time validation
contactForm.querySelectorAll('input, textarea').forEach(input => {
    input.addEventListener('input', () => {
        if (input.value.trim()) {
            input.classList.remove('is-invalid');
        }
        if (input.type === 'email') {
            if (validateEmail(input.value)) {
                input.classList.remove('is-invalid');
            }
        }
    });
}); 