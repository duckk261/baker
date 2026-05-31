<?php include 'header.php'; ?>

<div class="container-xxl py-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="bg-light rounded p-5" style="background-color: #fdfaf6 !important;">
                    <h1 class="text-center mb-4" style="font-family: 'Playfair Display', serif; font-weight: bold;">Register</h1>
                    
                    <form action="index.php?page=user&action=register" method="POST">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                            <label for="username">Username</label>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Full Name" required>
                            <label for="full_name">Full Name</label>
                        </div>

                     <div class="form-floating mb-3">
    <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
    <label for="email">Email Address</label>
</div>

<div class="form-floating mb-3">
    <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone Number" required>
    <label for="phone">Phone Number</label>
</div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                            <label for="password">Password</label>
                        </div>

                        <div class="form-floating mb-4">
                            <textarea class="form-control" placeholder="Address" id="address" name="address" style="height: 100px" required></textarea>
                            <label for="address">Address</label>
                        </div>

                        <button class="btn btn-primary rounded-pill w-100 py-3 mb-4" type="submit" style="font-weight: bold; font-size: 1.1rem;">Register</button>
                        
                        <div class="text-center">
                            Already have an account? <a href="index.php?page=login" class="text-primary" style="text-decoration: none; font-weight: 500;">Login here</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>