<?php include 'header.php'; ?>

<div class="container-xxl py-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 wow fadeInUp" data-wow-delay="0.1s">
                <div class="bg-light rounded p-5" style="background-color: #fdfaf6 !important;">
                    <h1 class="text-center mb-4" style="font-family: 'Playfair Display', serif; font-weight: bold;">Login</h1>
                    
                    <form method="POST" action="index.php?page=user&action=login">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                            <label for="username">Username</label>
                        </div>
                       
                        <div class="form-floating mb-2">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                            <label for="password">Password</label>
                        </div>

                        <div class="d-flex justify-content-end mb-4">
                            <a href="index.php?page=forgot_password" class="text-primary" style="text-decoration: none; font-size: 0.95rem; font-weight: 500;">Forgot Password?</a>
                        </div>

                        <button class="btn btn-primary rounded-pill w-100 py-3 mb-4" type="submit" style="font-weight: bold; font-size: 1.1rem;">Login</button>

                        <div class="text-center">
                            New member? <a href="index.php?page=register" class="text-primary" style="text-decoration: none; font-weight: 500;">Register here</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>