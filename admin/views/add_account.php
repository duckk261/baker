<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark">Add New Account</h3>
        <a href="index.php?page=accounts" class="btn btn-secondary shadow-sm"><i class="fas fa-arrow-left me-2"></i>Back</a>
    </div>

    <div class="card shadow-sm border-0" style="max-width: 900px;">
        <div class="card-body p-4">
            <form method="POST" action="index.php?page=add_account">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Full Name</label>
                        <input type="text" name="full_name" class="form-control border-primary" placeholder="Enter full name..." required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Username</label>
                        <input type="text" name="username" class="form-control border-primary" placeholder="Enter username..." required>
                    </div>
                </div>

                <div class="row">
                   <div class="col-md-6 mb-3">
    <label class="form-label fw-bold">Password</label>
    <div class="input-group">
        <input type="password" name="password" id="password" class="form-control border-primary" placeholder="Enter password..." required>
        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password" style="border-color: #dee2e6;">
            <i class="fas fa-eye-slash text-muted"></i>
        </button>
    </div>
</div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control border-primary" placeholder="e.g., email@gmail.com" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Phone Number</label>
                        <input type="text" name="phone_number" class="form-control border-primary" placeholder="Enter phone number...">
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">Role</label>
                        <select name="role" class="form-select border-primary">
                            <option value="User">User</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Address</label>
                    <textarea name="address" class="form-control border-primary" rows="2" placeholder="Enter delivery address/contact information..."></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary px-4 fw-bold fs-6"><i class="fas fa-plus me-2"></i>Create Account</button>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const toggleBtns = document.querySelectorAll('.toggle-password');
    toggleBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            } else {
                input.type = "password";
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            }
        });
    });
});
</script>