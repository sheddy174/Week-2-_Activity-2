$(document).ready(function() {
    // Form submission handler
    $('#register-form').submit(function(e) {
        e.preventDefault();
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.text();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Registering...');
        
        // Get form data
        const formData = {
            full_name: $('#full_name').val().trim(),
            email: $('#email').val().trim(),
            password: $('#password').val(),
            country: $('#country').val().trim(),
            city: $('#city').val().trim(),
            contact_number: $('#contact_number').val().trim()
        };
        
        // Client-side validation
        const validationResult = validateForm(formData);
        if (!validationResult.isValid) {
            showError(validationResult.message);
            resetSubmitButton(submitBtn, originalText);
            return;
        }
        
        // Submit form via AJAX
        $.ajax({
            url: '../actions/register_customer_action.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                resetSubmitButton(submitBtn, originalText);
                
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Registration Successful!',
                        text: response.message,
                        confirmButtonColor: '#D19C97',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'login.php';
                        }
                    });
                } else {
                    showError(response.message);
                }
            },
            error: function(xhr, status, error) {
                resetSubmitButton(submitBtn, originalText);
                console.error('AJAX Error:', {xhr, status, error});
                showError('An error occurred while processing your request. Please try again.');
            }
        });
    });
    
    // Real-time email validation
    $('#email').on('blur', function() {
        const email = $(this).val().trim();
        if (email && isValidEmail(email)) {
            checkEmailAvailability(email);
        }
    });
    
    // Real-time password strength indicator
    $('#password').on('input', function() {
        const password = $(this).val();
        updatePasswordStrength(password);
    });
});

/**
 * Validate form data on client side
 * @param {Object} data Form data object
 * @return {Object} Validation result with isValid boolean and message
 */
function validateForm(data) {
    // Check for empty fields
    for (const [key, value] of Object.entries(data)) {
        if (!value) {
            return {
                isValid: false,
                message: `${key.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())} is required`
            };
        }
    }
    
    // Validate full name (minimum 2 chars, letters and spaces only)
    if (data.full_name.length < 2 || !/^[a-zA-Z\s]+$/.test(data.full_name)) {
        return {
            isValid: false,
            message: 'Full name must be at least 2 characters and contain only letters and spaces'
        };
    }
    
    // Validate email format
    if (!isValidEmail(data.email)) {
        return {
            isValid: false,
            message: 'Please enter a valid email address'
        };
    }
    
    // Validate password strength
    const passwordValidation = validatePassword(data.password);
    if (!passwordValidation.isValid) {
        return passwordValidation;
    }
    
    // Validate country (letters and spaces only)
    if (data.country.length < 2 || !/^[a-zA-Z\s]+$/.test(data.country)) {
        return {
            isValid: false,
            message: 'Country must be at least 2 characters and contain only letters and spaces'
        };
    }
    
    // Validate city (letters and spaces only)
    if (data.city.length < 2 || !/^[a-zA-Z\s]+$/.test(data.city)) {
        return {
            isValid: false,
            message: 'City must be at least 2 characters and contain only letters and spaces'
        };
    }
    
    // Validate contact number (10-15 digits, may include + for country code)
    if (!/^\+?[1-9]\d{9,14}$/.test(data.contact_number)) {
        return {
            isValid: false,
            message: 'Contact number must be 10-15 digits and may include country code'
        };
    }
    
    return { isValid: true, message: '' };
}

/**
 * Validate email format using regex
 * @param {string} email Email address
 * @return {boolean} True if valid email format
 */
function isValidEmail(email) {
    const emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
    return emailRegex.test(email);
}

/**
 * Validate password strength
 * @param {string} password Password string
 * @return {Object} Validation result
 */
function validatePassword(password) {
    if (password.length < 8) {
        return {
            isValid: false,
            message: 'Password must be at least 8 characters long'
        };
    }
    
    if (!/(?=.*[a-z])/.test(password)) {
        return {
            isValid: false,
            message: 'Password must contain at least one lowercase letter'
        };
    }
    
    if (!/(?=.*[A-Z])/.test(password)) {
        return {
            isValid: false,
            message: 'Password must contain at least one uppercase letter'
        };
    }
    
    if (!/(?=.*\d)/.test(password)) {
        return {
            isValid: false,
            message: 'Password must contain at least one number'
        };
    }
    
    if (!/(?=.*[@$!%*?&])/.test(password)) {
        return {
            isValid: false,
            message: 'Password must contain at least one special character (@$!%*?&)'
        };
    }
    
    return { isValid: true, message: '' };
}

/**
 * Check email availability via AJAX
 * @param {string} email Email to check
 */
function checkEmailAvailability(email) {
    $.ajax({
        url: '../actions/check_email_action.php',
        type: 'POST',
        data: { email: email },
        dataType: 'json',
        success: function(response) {
            const emailField = $('#email');
            const feedbackDiv = $('#email-feedback');
            
            // Remove existing feedback
            feedbackDiv.remove();
            emailField.removeClass('is-valid is-invalid');
            
            if (response.exists) {
                emailField.addClass('is-invalid');
                emailField.after('<div id="email-feedback" class="invalid-feedback">This email is already registered</div>');
            } else {
                emailField.addClass('is-valid');
                emailField.after('<div id="email-feedback" class="valid-feedback">Email is available</div>');
            }
        }
    });
}

/**
 * Update password strength indicator
 * @param {string} password Password string
 */
function updatePasswordStrength(password) {
    const strengthDiv = $('#password-strength');
    
    if (!password) {
        strengthDiv.hide();
        return;
    }
    
    let strength = 0;
    let strengthText = '';
    let strengthClass = '';
    
    // Calculate strength
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    if (/[@$!%*?&]/.test(password)) strength++;
    
    // Determine strength level
    switch (strength) {
        case 0:
        case 1:
            strengthText = 'Very Weak';
            strengthClass = 'text-danger';
            break;
        case 2:
            strengthText = 'Weak';
            strengthClass = 'text-warning';
            break;
        case 3:
            strengthText = 'Fair';
            strengthClass = 'text-info';
            break;
        case 4:
            strengthText = 'Good';
            strengthClass = 'text-primary';
            break;
        case 5:
            strengthText = 'Strong';
            strengthClass = 'text-success';
            break;
    }
    
    // Show/update strength indicator
    if (strengthDiv.length === 0) {
        $('#password').after('<div id="password-strength" class="form-text"></div>');
    }
    
    $('#password-strength')
        .removeClass('text-danger text-warning text-info text-primary text-success')
        .addClass(strengthClass)
        .text(`Password Strength: ${strengthText}`)
        .show();
}

/**
 * Show error message using SweetAlert
 * @param {string} message Error message
 */
function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        text: message,
        confirmButtonColor: '#D19C97'
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