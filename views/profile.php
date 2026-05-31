<?php 
require_once 'app/models/UserModel.php';
require_once 'app/classes/Database.php';
$db = Database::getInstance();
$userModel = new UserModel($db);

$user_info = $userModel->getUserProfile($_SESSION['account_id']);

include 'header.php'; 
?>

<div class="container-xxl py-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 bg-light rounded p-5">
                <h1 class="display-6 text-center mb-4">My Profile</h1>
                
                <div id="profile-msg" class="alert d-none"></div>
                
                <form id="profileForm">
                    <div class="mb-3">
                        <label class="form-label text-muted">Full Name</label>
                        <input type="text" name="ten_kh" class="form-control" value="<?php echo $user_info['full_name']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Email Address</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $user_info['email']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="<?php echo $user_info['phone_number']; ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted">Shipping Address</label>
                        <textarea name="address" class="form-control" style="height: 100px"><?php echo $user_info['address']; ?></textarea>
                    </div>
                    <hr class="my-4" style="border-color: #ddd;">
<h4 class="mb-3" style="font-family: 'Playfair Display', serif; font-weight: bold;">Change Password</h4>
<p class="text-muted mb-4" style="font-size: 0.9rem;">(Leave blank if you don't want to change your password)</p>

<div class="mb-3">
    <label class="form-label" style="color: #666;">Current Password</label>
    <input type="password" class="form-control" name="current_password" placeholder="Enter current password">
</div>

<div class="mb-3">
    <label class="form-label" style="color: #666;">New Password</label>
    <input type="password" class="form-control" name="new_password" placeholder="Enter new password">
</div>

<div class="mb-4">
    <label class="form-label" style="color: #666;">Confirm New Password</label>
    <input type="password" class="form-control" name="confirm_password" placeholder="Re-enter new password">
</div>
                    <button type="button" onclick="updateProfileAJAX()" class="btn btn-primary w-100 py-3">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function updateProfileAJAX() {
    let form = document.getElementById('profileForm');
    let formData = new FormData(form);
    let msgBox = document.getElementById('profile-msg');

    fetch('index.php?page=user&action=update_profile', {
        method: 'POST',
        body: formData
    })
    .then(async (response) => {
        // Kiểm tra xem dữ liệu trả về có bị lỗi PHP ngầm không
        const isJson = response.headers.get('content-type')?.includes('application/json');
        const data = isJson ? await response.json() : null;

        if (!response.ok || !data) {
            const errText = await response.text();
            throw new Error(errText || 'Lỗi không xác định từ Server');
        }
        return data;
    })
    .then(data => {
        msgBox.classList.remove('d-none', 'alert-danger', 'alert-success');
        
        if (data.status === 'success') {
            alert(data.message); 
            location.reload(); 
        } else {
            msgBox.classList.add('alert-danger');
            msgBox.innerText = data.message;
        }
    })
    .catch(err => {
        console.error('Chi tiết lỗi:', err);
        alert('Server trả về lỗi lạ (ấn F12 sang tab Console để xem chi tiết nhé)!');
    });
}
</script>

<?php include 'footer.php'; ?>