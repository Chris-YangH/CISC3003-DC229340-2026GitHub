/**
 * CISC3003 Mid-Term Exam Paper 01 Part 04
 * Signup and Login Form JavaScript
 * Student ID: DC229340
 * Student Name: YANG HAO
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    const formContainer = document.querySelector('.form-container');
    const signInBtn = document.getElementById('signInBtn');
    const signUpBtn = document.getElementById('signUpBtn');
    const signInForm = document.getElementById('signInForm');
    const signUpForm = document.getElementById('signUpForm');

    // Event listener for Sign Up button (on overlay panel)
    signUpBtn.addEventListener('click', function() {
        formContainer.classList.add('active');
    });

    // Event listener for Sign In button (on overlay panel)
    signInBtn.addEventListener('click', function() {
        formContainer.classList.remove('active');
    });

    // Handle Sign In form submission
    signInForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const email = document.getElementById('signin-email').value;
        const password = document.getElementById('signin-password').value;

        // Validate required fields
        if (!email || !password) {
            alert('Please fill in all required fields!');
            return;
        }

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Please enter a valid email address!');
            return;
        }

        // Simulate sign in (in real application, this would send data to server)
        alert('Sign In successful!\nEmail: ' + email);
        
        // Reset form
        signInForm.reset();
    });

    // Handle Sign Up form submission
    signUpForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const fullName = document.getElementById('signup-fullname').value;
        const email = document.getElementById('signup-email').value;
        const password = document.getElementById('signup-password').value;

        // Validate required fields
        if (!fullName || !email || !password) {
            alert('Please fill in all required fields!');
            return;
        }

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Please enter a valid email address!');
            return;
        }

        // Password validation (at least 6 characters)
        if (password.length < 6) {
            alert('Password must be at least 6 characters long!');
            return;
        }

        // Simulate sign up (in real application, this would send data to server)
        alert('Sign Up successful!\nFull Name: ' + fullName + '\nEmail: ' + email);
        
        // Reset form and switch back to sign in
        signUpForm.reset();
        formContainer.classList.remove('active');
    });

    // Add input focus effects
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });

        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });

    // Social icon click handlers (demo)
    const socialIcons = document.querySelectorAll('.social-icon');
    socialIcons.forEach(icon => {
        icon.addEventListener('click', function(e) {
            e.preventDefault();
            const platform = this.querySelector('i').classList[1];
            alert('Sign in with ' + platform.replace('fa-', '').replace('-f', '').replace('-in', '') + ' coming soon!');
        });
    });

    // Forgot password handler
    const forgotPassword = document.querySelector('.forgot-password');
    if (forgotPassword) {
        forgotPassword.addEventListener('click', function(e) {
            e.preventDefault();
            alert('Password reset functionality coming soon!');
        });
    }

    console.log('CISC3003 Login/Signup Form loaded successfully!');
});
