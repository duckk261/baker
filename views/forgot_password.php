<?php include 'header.php'; ?>

<div class="container-xxl py-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 wow fadeInUp" data-wow-delay="0.1s">
                <div class="bg-light rounded p-5" style="background-color: #fdfaf6 !important;">
                    <h1 class="text-center mb-4" style="font-family: 'Playfair Display', serif; font-weight: bold;">Forgot Password</h1>
                    <p class="text-center mb-4">Please enter your registered email address. We will send a new password to your inbox.</p>
                    
                    <form action="index.php?page=user&action=forgot" method="POST">
                        <div class="form-floating mb-4">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                            <label for="email">Email Address</label>
                        </div>
                        
                        <button class="btn btn-primary rounded-pill w-100 py-3 mb-4" type="submit" style="font-weight: bold; font-size: 1.1rem;">Send New Password</button>
                        
                        <div class="text-center">
                            <a href="index.php?page=login" class="text-primary" style="text-decoration: none; font-weight: 500;">Back to Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>