<?php 
require_once 'app/classes/Database.php';
$db = Database::getInstance();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = mysqli_query($db, "SELECT * FROM products WHERE product_id = '$id'");
$product = mysqli_fetch_assoc($query);

if (!$product) {
    echo "<div class='container py-5 text-center'><h3>Sản phẩm không tồn tại!</h3></div>";
    exit;
}

$p_name = $product['product_name'] ?? 'Bánh ngon';
$p_price = $product['price'] ?? 0;
$p_img = $product['image'] ?? 'default.jpg';
$p_stock = $product['stock_quantity'] ?? 0;

// LẤY MÔ TẢ TỪ DATABASE (Nếu rỗng thì hiện câu mặc định)
$p_desc = $product['description'] ?? 'Chưa có mô tả chi tiết cho sản phẩm này.';

// Kiểm tra trạng thái tim của riêng cái bánh này
$is_favorite = false;
if (isset($_SESSION['account_id'])) {
    $acc_id = $_SESSION['account_id'];
    $fav_check = mysqli_query($db, "SELECT * FROM favorites WHERE account_id = '$acc_id' AND product_id = '$id'");
    if (mysqli_num_rows($fav_check) > 0) {
        $is_favorite = true;
    }
}
// Set icon và chữ cho nút Yêu thích
$heart_icon = $is_favorite ? 'fas fa-heart' : 'far fa-heart';
$btn_text = $is_favorite ? 'Favorited' : 'Add to Wishlist';

include 'header.php'; // Trỏ đúng đường dẫn header nếu cần
?>

<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5 wow fadeIn" data-wow-delay="0.1s">
                <img class="img-fluid rounded shadow" src="assets/img/<?php echo $p_img; ?>" alt="<?php echo $p_name; ?>" style="width: 100%; object-fit: cover; aspect-ratio: 1/1;" onerror="this.onerror=null; this.src='https://placehold.co/500x500?text=No+Image';">
            </div>
            
            <div class="col-lg-7 wow fadeIn" data-wow-delay="0.5s">
                <h1 class="display-5 mb-4 text-primary"><?php echo $p_name; ?></h1>
                <h2 class="text-danger mb-4"><?php echo number_format($p_price, 0, ',', '.'); ?> VNĐ</h2>
                
                <p class="mb-4 fs-5 text-muted" style="line-height: 1.8;">
                    <?php echo nl2br($p_desc); ?>
                </p>
                
                <p class="mb-4"><strong>Tình trạng:</strong> 
                    <?php if ($p_stock > 0): ?>
                        <span class="badge bg-success fs-6">Còn hàng (<?php echo $p_stock; ?>)</span>
                    <?php else: ?>
                        <span class="badge bg-danger fs-6">Hết hàng</span>
                    <?php endif; ?>
                </p>

                <div class="d-flex align-items-center mb-4 pt-3">
                    <div class="d-flex me-3">
                        <button type="button" class="btn btn-primary py-2 px-4 fw-bold" onclick="addToCart(event, <?php echo $id; ?>)" <?php echo ($p_stock <= 0) ? 'disabled' : ''; ?>>
                            <i class="fa fa-cart-plus me-2"></i>Thêm Giỏ Hàng
                        </button>
                    </div>
                    
                    <a href="javascript:void(0);" onclick="toggleFavorite(event, <?php echo $id; ?>, this)" class="btn btn-outline-primary py-2 px-4 fw-bold">
                        <i class="<?php echo $heart_icon; ?> me-2"></i><span class="fav-text"><?php echo $btn_text; ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>