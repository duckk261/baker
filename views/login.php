<?php include 'header.php'; ?>

<div class="container-xxl py-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 wow fadeInUp" data-wow-delay="0.1s">
                <div class="bg-light rounded p-5" style="background-color: #fdfaf6 !important;">
                    <h1 class="text-center mb-4" style="font-family: 'Playfair Display', serif; font-weight: bold;">Đăng Nhập</h1>
                    
                    <form method="POST" action="index.php?page=user&action=login">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                            <label for="username">Tên Đăng Nhập</label>
                        </div>
                       
                        <div class="input-group mb-3">
                            <div class="form-floating flex-grow-1">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                                <label for="password">Password</label>
                            </div>
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password" style="border-color: #ced4da; border-left: none;">
                                <i class="fas fa-eye-slash text-muted"></i>
                            </button>
                        </div>

                        <div class="d-flex justify-content-end mb-4">
                            <a href="index.php?page=forgot_password" class="text-primary" style="text-decoration: none; font-size: 0.95rem; font-weight: 500;">Forgot Password?</a>
                        </div>

                        <button class="btn btn-primary rounded-pill w-100 py-3 mb-4" type="submit" style="font-weight: bold; font-size: 1.1rem;">Đăng Nhập</button>

                        <div class="text-center">
                            New member? <a href="index.php?page=register" class="text-primary" style="text-decoration: none; font-weight: 500;">Register here</a>
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
                icon.classList.add('fa-eye'); // Mắt mở
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