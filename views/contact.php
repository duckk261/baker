<?php 
require_once 'app/classes/Database.php';
$db = Database::getInstance();

// Lấy thông tin user nếu đã đăng nhập để tự động điền sẵn vào Form
$full_name = '';
$email = '';
$phone = '';

if (isset($_SESSION['account_id'])) {
    $acc_id = $_SESSION['account_id'];
    $user = null;
    
    // Tách riêng từng câu truy vấn để dò mìn, tránh lỗi Unknown Column
    $queries = [
        "SELECT * FROM accounts WHERE id = '$acc_id'",
        "SELECT * FROM accounts WHERE account_id = '$acc_id'",
        "SELECT * FROM accounts WHERE user_id = '$acc_id'",
        "SELECT * FROM customers WHERE customer_id = '$acc_id'",
        "SELECT * FROM customers WHERE account_id = '$acc_id'"
    ];
    
    foreach ($queries as $sql) {
        try {
            // Dùng @ để ém nhẹm cảnh báo, catch Exception nếu MySQL ném lỗi
            $res = @mysqli_query($db, $sql);
            if ($res && mysqli_num_rows($res) > 0) {
                $user = mysqli_fetch_assoc($res);
                break; // Tìm thấy đúng người là dừng dò luôn
            }
        } catch (Exception $e) {
            // Cột không tồn tại thì âm thầm bỏ qua, đi dò câu tiếp theo
        }
    }

    // Nếu lôi được data người dùng ra thì gán vào biến
    if ($user) {
        $full_name = $user['account_name'] ?? $user['customer_name'] ?? $user['fullname'] ?? $user['name'] ?? '';
        $email = $user['email'] ?? '';
        $phone = $user['phone'] ?? $user['phone_number'] ?? '';
    }
}

include 'header.php'; 
?>

<div class="container-xxl py-6" style="margin-top: 50px;">
    <div class="container">
        <div class="row g-5">
            
            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                <h2 class="mb-4" style="font-family: 'Playfair Display', serif; font-weight: 700; color: #222; font-size: 2.5rem;">Thông Tin Liên Lạc</h2>
                <p class="mb-5 text-muted" style="line-height: 1.8; font-size: 1.05rem;">
                    Tiệm bánh tọa lạc ngay tại trung tâm thành phố Hà Nội – con phố Tuệ Tĩnh ấm cúng với nhiều cửa hàng mua sắm và chỗ đậu xe rộng rãi, thuận tiện cho quý khách ghé thăm.
                </p>

                <div class="d-flex mb-4 align-items-start">
                    <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle shadow-sm" style="width: 55px; height: 55px; background-color: #c4a16b; color: white;">
                        <i class="fas fa-map-marker-alt fs-5"></i>
                    </div>
                    <div class="ms-4">
                        <h4 class="mb-2" style="font-family: 'Playfair Display', serif; font-weight: bold; color: #333;">Địa Chỉ</h4>
                        <p class="mb-0 text-muted">45 P. Tuệ Tĩnh, Bùi Thị Xuân, Hai Bà Trưng, Hà Nội, Việt Nam</p>
                    </div>
                </div>

                <div class="d-flex mb-4 align-items-start">
                    <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle shadow-sm" style="width: 55px; height: 55px; background-color: #c4a16b; color: white;">
                        <i class="fas fa-phone-alt fs-5"></i>
                    </div>
                    <div class="ms-4">
                        <h4 class="mb-2" style="font-family: 'Playfair Display', serif; font-weight: bold; color: #333;">Số Điện Thoại</h4>
                        <p class="mb-0 text-muted">Hotline: <span style="color: #c4a16b; font-weight: 500;">0388888888</span></p>
                        <p class="mb-0 text-muted" style="padding-left: 56px;"><span style="color: #c4a16b; font-weight: 500;">0868222222</span></p>
                        <p class="mb-0 text-muted mt-2">Telephone: <span style="color: #c4a16b; font-weight: 500;">0127888888</span></p>
                    </div>
                </div>

                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle shadow-sm" style="width: 55px; height: 55px; background-color: #c4a16b; color: white;">
                        <i class="fas fa-envelope fs-5"></i>
                    </div>
                    <div class="ms-4">
                        <h4 class="mb-2" style="font-family: 'Playfair Display', serif; font-weight: bold; color: #333;">Email</h4>
                        <p class="mb-0 text-muted">bakery@support.com</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                <h2 class="mb-4" style="font-family: 'Playfair Display', serif; font-weight: 700; color: #222; font-size: 2.5rem;">Form Liên Hệ</h2>
                
                <form action="index.php?action=submit_contact" method="POST" class="p-4 rounded shadow-sm" style="background-color: #fff; border: 1px solid #f0f0f0;">
                    <div class="row g-3">
                        <div class="col-12">
                            <input type="text" name="name" class="form-control bg-transparent py-3" style="border: 1px solid #ddd;" placeholder="Tên của bạn *" value="<?php echo htmlspecialchars($full_name); ?>" required>
                        </div>
                        <div class="col-12">
                            <input type="email" name="email" class="form-control bg-transparent py-3" style="border: 1px solid #ddd;" placeholder="Địa chỉ email *" value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>
                        <div class="col-12">
                            <input type="text" name="phone" class="form-control bg-transparent py-3" style="border: 1px solid #ddd;" placeholder="Số điện thoại *" value="<?php echo htmlspecialchars($phone); ?>" required>
                        </div>
                        <div class="col-12">
                            <textarea name="message" class="form-control bg-transparent py-3" style="border: 1px solid #ddd;" rows="5" placeholder="Lời nhắn *" required></textarea>
                        </div>
                        <div class="col-12">
                            <button class="btn text-white py-3 px-5 mt-2 fw-bold w-100" type="submit" style="background-color: #c4a16b; border-radius: 4px; font-size: 1.1rem;">Gửi lời nhắn</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php include 'footer.php'; ?>