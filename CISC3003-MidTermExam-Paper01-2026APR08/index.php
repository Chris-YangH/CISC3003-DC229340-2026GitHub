<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DC229340 - YANG HAO</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <!-- Sign In Form -->
            <div class="form-section sign-in-section">
                <h2 class="form-title">Log In</h2>
                <div class="social-icons">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <p class="form-text">Use your account to sign in</p>
                <form id="signInForm">
                    <div class="input-group">
                        <input type="email" id="signin-email" name="email" placeholder="Email" required>
                    </div>
                    <div class="input-group">
                        <input type="password" id="signin-password" name="password" placeholder="Password" required>
                    </div>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                    <button type="submit" class="btn btn-primary">SIGN IN</button>
                </form>
            </div>

            <!-- Sign Up Form -->
            <div class="form-section sign-up-section">
                <h2 class="form-title">Join Us</h2>
                <div class="social-icons">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <p class="form-text">Use your email to sign up</p>
                <form id="signUpForm">
                    <div class="input-group">
                        <input type="text" id="signup-fullname" name="fullname" placeholder="Full Name" required>
                    </div>
                    <div class="input-group">
                        <input type="email" id="signup-email" name="email" placeholder="Email" required>
                    </div>
                    <div class="input-group">
                        <input type="password" id="signup-password" name="password" placeholder="Create Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">REGISTER</button>
                </form>
            </div>

            <!-- Overlay Section -->
            <div class="overlay-container">
                <div class="overlay">
                    <!-- Left Overlay Panel -->
                    <div class="overlay-panel overlay-left">
                        <h2 class="overlay-title">Hello, Again!</h2>
                        <div class="overlay-image">
                            <img src="images/signin-illustration.png" alt="Sign In Illustration">
                        </div>
                        <p class="overlay-text">Log in to stay connected with us</p>
                        <button class="btn btn-outline" id="signInBtn">SIGN IN</button>
                    </div>

                    <!-- Right Overlay Panel -->
                    <div class="overlay-panel overlay-right">
                        <h2 class="overlay-title">Welcome!</h2>
                        <div class="overlay-image">
                            <img src="images/signup-illustration.png" alt="Sign Up Illustration">
                        </div>
                        <p class="overlay-text">Enter your details to start your journey</p>
                        <button class="btn btn-outline" id="signUpBtn">SIGN UP</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>CISC3003 Web Programming: DC229340 YANG HAO 2026</p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
