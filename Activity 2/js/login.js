$(document).ready(function() {
    // Login form submission handler
    $('#login-form').submit(function(e) {
        e.preventDefault();
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.text();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Logging in...');
        
        // Get form data
        const formData = {
            email: $('#email').val().trim(),
            password: $('#password').val()
        };
        
        // Client-side validation
        const validationResult = validateLoginForm(formData);
        if (!validationResult.isValid) {
            showError(validationResult.message);
            resetSubmitButton(submitBtn, originalText);
            return;
        }
        
        // Submit form via AJAX
        $.ajax({
            url: '../actions/login_customer_action.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                resetSubmitButton(submitBtn, originalText);
                
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful!',
                        text: response.message,
                        confirmButtonColor: '#2E86AB',
                        allowOutsideClick: false,
                        timer: 2000,
                        timerProgressBar: true
                    }).then((result) => {
                        // Redirect to index.php as required
                        window.location.href = response.redirect || '../index.php';
                    });
                } else {
                    showError(response.message);
                }
            },
            error: function(xhr, status, error) {
                resetSubmitButton(submitBtn, originalText);
                console.error('AJAX Error:', {xhr, status, error});
                
                let errorMessage = 'An error occurred while processing your request.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                showError(errorMessage);
            }
        });
    });
    
    // Remember me functionality
    $('#rememberMe').change(function() {
        if ($(this).is(':checked')) {
            // Store preference in localStorage
            localStorage.setItem('rememberLogin', 'true');
        } else {
            localStorage.removeItem('rememberLogin');
        }
    });
    
    // Load remembered email if available
    if (localStorage.getItem('rememberLogin') === 'true') {
        const rememberedEmail = localStorage.getItem('rememberedEmail');
        if (rememberedEmail) {
            $('#email').val(rememberedEmail);
            $('#rememberMe').prop('checked', true);
        }
    }
    
    // Save email when remember me is checked
    $('#email').on('blur', function() {
        if ($('#rememberMe').is(':checked')) {
            localStorage.setItem('rememberedEmail', $(this).val());
        }
    });
});

/**
 * Validate login form data on client side
 * @param {Object} data Form data object
 * @return {Object} Validation result with isValid boolean and message
 */
function validateLoginForm(data) {
    // Check for empty fields
    if (!data.email) {
        return {
            isValid: false,
            message: 'Email address is required'
        };
    }
    
    if (!data.password) {
        return {
            isValid: false,
            message: 'Password is required'
        };
    }
    
    // Validate email format using regex
    const emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
    if (!emailRegex.test(data.email)) {
        return {
            isValid: false,
            message: 'Please enter a valid email address'
        };
    }
    
    // Password length check (minimum validation)
    if (data.password.length < 1) {
        return {
            isValid: false,
            message: 'Password cannot be empty'
        };
    }
    
    return { isValid: true, message: '' };
}

/**
 * Show error message using SweetAlert
 * @param {string} message Error message
 */
function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Login Failed',
        text: message,
        confirmButtonColor: '#2E86AB'
    });
}

/**
 * Show success message using SweetAlert
 * @param {string} message Success message
 */
function showSuccess(message) {
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: message,
        confirmButtonColor: '#2E86AB'
    });
}

/**
 * Reset submit button to original state
 * @param {jQuery} button Button element
 * @param {string} originalText Original button text
 */
function resetSubmitButton(button, originalText) {
    button.prop('disabled', false).html(originalText);
}
