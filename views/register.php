<?php include 'header.php'; ?>

<div class="container-xxl py-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="bg-light rounded p-5" style="background-color: #fdfaf6 !important;">
                    <h1 class="text-center mb-4" style="font-family: 'Playfair Display', serif; font-weight: bold;">Đăng Ký</h1>
                    
                    <form action="index.php?page=user&action=register" method="POST">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                            <label for="username">Tên Đăng Nhập</label>
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

                    <div class="input-group mb-3">
                            <div class="form-floating flex-grow-1">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                <label for="password">Password</label>
                            </div>
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password" style="border-color: #ced4da; border-left: none;">
                                <i class="fas fa-eye-slash text-muted"></i>
                            </button>
                        </div>

                        <div class="form-floating mb-4">
                            <textarea class="form-control" placeholder="Address" id="address" name="address" style="height: 100px" required></textarea>
                            <label for="address">Address</label>
                        </div>

                        <button class="btn btn-primary rounded-pill w-100 py-3 mb-4" type="submit" style="font-weight: bold; font-size: 1.1rem;">Đăng Ký</button>
                        
                        <div class="text-center">
                            Already have an account? <a href="index.php?page=login" class="text-primary" style="text-decoration: none; font-weight: 500;">Login here</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const toggleBtns = document.querySelectorAll('.toggle-password');

    toggleBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Lấy ID của ô input
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            const icon = this.querySelector('i');

            // Đảo trạng thái type (text <-> password)
            if (passwordInput.type === "password") {
                passwordInput.type = "text"; 
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                passwordInput.type = "password"; 
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash'); // Mắt bị gạch
            }
        });
    });
});
</script>
<?php include 'footer.php'; ?>