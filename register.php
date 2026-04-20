<?php include 'header.php'; ?>

<div class="container-xxl py-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 bg-light rounded p-5">
                <h1 class="display-6 text-center mb-4">Register</h1>
                
                <div id="alert-msg"></div>
                
                <form id="registerForm">
                    <div class="row g-3">
                        <div class="col-12"><input type="text" name="ten_kh" class="form-control" placeholder="Your Full Name" required></div>
                        <div class="col-md-6"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
                        <div class="col-md-6"><input type="text" name="phone" class="form-control" placeholder="Phone Number" required></div>
                        <div class="col-12"><input type="text" name="address" class="form-control" placeholder="Address" required></div>
                        <div class="col-12 border-top pt-3"><strong>Account Information</strong></div>
                        <div class="col-md-6"><input type="text" name="username" class="form-control" placeholder="Username" required></div>
                        <div class="col-md-6"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100 py-3">Create Account</button>
                        </div>
                        <p class="text-center mt-3">Already have an account? <a href="login.php">Login here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Chặn F5 trang
    
    let formData = new FormData(this);
    let alertBox = document.getElementById('alert-msg');

    fetch('api/register_action.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            alertBox.innerHTML = `<div class='alert alert-success'>${data.message} Redirecting to login...</div>`;
            setTimeout(() => { window.location.href = 'login.php'; }, 2000);
        } else {
            alertBox.innerHTML = `<div class='alert alert-danger'>${data.message}</div>`;
        }
    })
    .catch(err => console.error('Error:', err));
});
</script>

<?php include 'footer.php'; ?>