<?php 
require_once 'app/classes/Database.php';
$db = Database::getInstance();
$acc_id = $_SESSION['account_id'];

// Fetch wishlist items for the logged-in user
$query = "SELECT p.*, f.favorite_id 
          FROM favorites f 
          JOIN products p ON f.product_id = p.product_id 
          WHERE f.account_id = '$acc_id' 
          ORDER BY (p.stock_quantity = 0) ASC, f.created_at DESC";
$fav_products = mysqli_query($db, $query);

include 'header.php'; // Đảm bảo đúng đường dẫn header
?>

<div class="container-xxl bg-light py-6 my-6 mt-0">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width: 500px;">
            <p class="text-primary text-uppercase mb-2">My Wishlist</p>
            <h1 class="display-6 mb-4">Your Favorite Products</h1>
        </div>
        
        <div class="row g-4">
            <?php 
            if ($fav_products && mysqli_num_rows($fav_products) > 0) {
                while($row = mysqli_fetch_assoc($fav_products)) {
                    $p_name = $row['product_name'] ?? 'Delicious Cake';
                    $p_price = $row['price'] ?? 0;
                    $p_img = $row['image'] ?? 'default.jpg';
                    $p_stock = $row['stock_quantity'] ?? 0;
                    $is_out_of_stock = ($p_stock <= 0);
            ?>
                <div class="col-lg-4 col-md-6">
                    <div class="product-item d-flex flex-column bg-white rounded overflow-hidden h-100 shadow-sm" <?php if($is_out_of_stock) echo 'style="opacity: 0.85;"'; ?>>
                        <div class="text-center p-4">
                            <h4 class="mb-3"><?php echo $p_name; ?></h4> 
                            <h5 class="text-danger mb-0"><?php echo number_format($p_price, 0, ',', '.'); ?> ₫</h5>
                        </div>

                        <div class="position-relative mt-auto">
                            <img class="img-fluid w-100" src="assets/img/<?php echo $p_img; ?>" alt="<?php echo $p_name; ?>" style="height: 250px; object-fit: cover; <?php if($is_out_of_stock) echo 'filter: grayscale(100%);'; ?>" onerror="this.onerror=null; this.src='https://placehold.co/250x250?text=No+Image';">
                            
                            <?php if ($is_out_of_stock): ?>
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(255,255,255,0.4); z-index: 2; pointer-events: none;">
                                    <span class="badge bg-danger text-white fs-5 px-4 py-2 shadow" style="border-radius: 8px; letter-spacing: 1px; font-weight: bold;">HẾT HÀNG</span>
                                </div>
                            <?php endif; ?>

                            <div class="product-overlay d-flex justify-content-center align-items-center" <?php if($is_out_of_stock) echo 'style="z-index: 3;"'; ?>>
                                <?php if (!$is_out_of_stock): ?>
                                <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="javascript:void(0);" onclick="addToCart(event, <?php echo $row['product_id']; ?>)" title="Add to Cart">
                                 <i class="fa fa-cart-plus text-primary"></i>
                                </a>
                                <?php else: ?>
                                <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1 disabled" href="javascript:void(0);" style="opacity: 0.5; pointer-events: none;" title="Out of Stock">
                                 <i class="fa fa-cart-plus text-secondary"></i>
                                </a>
                                <?php endif; ?>
                                
                                <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="index.php?page=product_detail&id=<?php echo $row['product_id']; ?>" title="View Details">
                                 <i class="fa fa-eye text-info"></i>
                                </a>

                                <a class="btn btn-lg-square btn-danger rounded-circle mx-1 border-0" href="index.php?action=remove_favorite&id=<?php echo $row['favorite_id']; ?>" title="Remove from Wishlist" onclick="confirmAction(event, this.href, 'Are you sure you want to remove this item from your wishlist?');">
                                 <i class="fa fa-trash text-white"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                }
            } else {
                echo "<div class='col-12 text-center py-5'>
                        <h4 class='text-muted mb-4'>Your wishlist is currently empty.</h4>
                        <a href='index.php?page=product' class='btn btn-primary rounded-pill px-4'>Explore Products</a>
                      </div>";
            }
            ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>