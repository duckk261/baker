<?php 
require_once '../db_connect.php'; 
require_once '../app/models/ProductModel.php';
$productModel = new ProductModel($conn);


$limit = 6; 
$current_page = isset($_GET['p']) ? (int)$_GET['p'] : 1; 
if ($current_page < 1) $current_page = 1;

<<<<<<< Updated upstream
$offset = ($current_page - 1) * $limit;
$total_products = $productModel->getTotalProducts();
=======
$cat_query = @mysqli_query($db, "SELECT * FROM categories WHERE status = 1");
$where_clauses = ["p.status = 1"]; // Chỉ lấy bánh đang mở bán

if ($cat_id > 0) {
    // Chỉ lấy bánh có category_id khớp với danh mục đang bật
    $where_clauses[] = "p.category_id = '$cat_id' AND p.category_id IN (SELECT category_id FROM categories WHERE status = 1)";
} else {
    // Nếu chọn "Tất cả", chỉ lấy bánh thuộc danh mục đang bật (status=1)
    $where_clauses[] = "p.category_id IN (SELECT category_id FROM categories WHERE status = 1)";
}

if ($is_search) {
    $where_clauses[] = "p.product_name LIKE '%" . mysqli_real_escape_string($db, $search_term) . "%'";
}

if ($price === 'under_50') {
    $where_clauses[] = "p.price < 50000";
} elseif ($price === '50_100') {
    $where_clauses[] = "p.price >= 50000 AND p.price <= 100000";
} elseif ($price === 'above_100') {
    $where_clauses[] = "p.price > 100000";
}

$where_sql = implode(' AND ', $where_clauses);

// ================= THUẬT TOÁN TÍNH SAO TRUNG BÌNH =================
$having_clause = "";
if ($rating === '5') {
    $having_clause = " HAVING AVG(r.rating) = 5"; // Gọi thẳng hàm AVG() thay vì dùng alias
} elseif ($rating === '4-5') {
    $having_clause = " HAVING AVG(r.rating) >= 4 AND AVG(r.rating) < 5"; 
} elseif ($rating === '3-4') {
    $having_clause = " HAVING AVG(r.rating) >= 3 AND AVG(r.rating) < 4"; 
} elseif ($rating === '1-3') {
    $having_clause = " HAVING AVG(r.rating) > 0 AND AVG(r.rating) < 3"; 
}

// Giữ nguyên đoạn đếm tổng số và truy vấn dữ liệu bên dưới...

// Đếm tổng số sản phẩm 
$count_sql = "SELECT COUNT(*) as total FROM (SELECT p.product_id FROM products p LEFT JOIN reviews r ON p.product_id = r.product_id WHERE $where_sql GROUP BY p.product_id $having_clause) as temp";
$count_res = @mysqli_query($db, $count_sql);
$total_products = $count_res ? mysqli_fetch_assoc($count_res)['total'] : 0;
>>>>>>> Stashed changes
$total_pages = ceil($total_products / $limit);

$all_products = $productModel->getProductsPaginated($limit, $offset);

include 'header.php'; 
?>

<div class="container-xxl bg-light py-6 my-6 mt-0">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
<<<<<<< Updated upstream
            <p class="text-primary text-uppercase mb-2">Our Menu</p>
            <h1 class="display-6 mb-4">Explore All Our Bakery Products</h1>
=======
            <p class="text-primary text-uppercase mb-2">Thực Đơn</p>
            <h1 class="display-6 mb-4">
                <?php echo $is_search ? 'Kết quả tìm kiếm: "' . htmlspecialchars($search_term) . '"' : 'Khám phá tất cả bánh của chúng tôi'; ?>
            </h1>
            <?php if ($total_products == 0): ?>
                <p class="text-muted">Không tìm thấy sản phẩm nào phù hợp với yêu cầu của bạn.</p>
                <a href="index.php?page=product" class="btn btn-primary mt-3">Xem tất cả sản phẩm</a>
            <?php endif; ?>
        </div>

        <div class="row justify-content-center mb-5 wow fadeInUp" data-wow-delay="0.2s">
            <div class="col-lg-8">
                <form action="index.php" method="GET" class="bg-white p-4 rounded shadow-sm border">
                    <input type="hidden" name="page" value="product">
                    <?php if($is_search): ?>
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_term); ?>">
                    <?php endif; ?>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold" style="color: #c4a16b;">Lọc theo Danh Mục</label>
<select name="category" class="form-select" style="border-color: #c4a16b; cursor: pointer;" onchange="filterProductsAJAX()">
    <option value="0">🎂 Tất cả danh mục</option>
    <?php 
    // TRUY VẤN TRỰC TIẾP KHÔNG ĐIỀU KIỆN ĐỂ TRÁNH TRẮNG DROPDOWN
    $all_cats = mysqli_query($db, "SELECT * FROM categories WHERE status = 1"); 
    if($all_cats && mysqli_num_rows($all_cats) > 0) {
        while($c = mysqli_fetch_assoc($all_cats)): 
            $c_id = $c['category_id'] ?? $c['id'];
            $c_name = $c['category_name'] ?? $c['name'];
            $selected = ($cat_id == $c_id) ? 'selected' : '';
            echo "<option value='{$c_id}' {$selected}>{$c_name}</option>";
        endwhile; 
    }
    ?>
</select>
                        </div>

                      <div class="col-md-4">
                            <label class="form-label fw-bold" style="color: #c4a16b;">Lọc theo Đánh Giá</label>
<select name="rating" class="form-select" style="border-color: #c4a16b; cursor: pointer;" onchange="filterProductsAJAX()">                                <option value="all">⭐ Tất cả đánh giá</option>
                                <option value="5" <?php echo ($rating === '5') ? 'selected' : ''; ?>>⭐⭐⭐⭐⭐ (5 Sao Tuyệt đối)</option>
                                <option value="4-5" <?php echo ($rating === '4-5') ? 'selected' : ''; ?>>⭐⭐⭐⭐ (Từ 4 đến dưới 5 Sao)</option>
                                <option value="3-4" <?php echo ($rating === '3-4') ? 'selected' : ''; ?>>⭐⭐⭐ (Từ 3 đến dưới 4 Sao)</option>
                                <option value="1-3" <?php echo ($rating === '1-3') ? 'selected' : ''; ?>>⭐⭐ (Dưới 3 Sao)</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-bold" style="color: #c4a16b;">Lọc theo Giá Cả</label>
                            <select name="price" class="form-select" style="border-color: #c4a16b; cursor: pointer;" onchange="filterProductsAJAX()">                                
                                <option value="all">💰 Tất cả mức giá</option>
                                <option value="under_50" <?php echo ($price === 'under_50') ? 'selected' : ''; ?>>Dưới 50.000đ</option>
                                <option value="50_100" <?php echo ($price === '50_100') ? 'selected' : ''; ?>>Từ 50.000đ - 100.000đ</option>
                                <option value="above_100" <?php echo ($price === 'above_100') ? 'selected' : ''; ?>>Trên 100.000đ</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
>>>>>>> Stashed changes
        </div>
        
        <div class="row g-4">
            <?php 
            if ($all_products && mysqli_num_rows($all_products) > 0) {
                while($row = mysqli_fetch_assoc($all_products)) {

                $image_map = [
                            'Bánh Mousse Chanh Dây' => 'mouse-chanh-day.jpg',
                            'Bánh Red Velvet Cream' => 'redvelet-cream.png',
                            'Bánh Dark Chocolate'   => 'dark-chocolate.jpg',
                            'Bánh Kem Phô Mai Việt Quất' => 'kem-pho-mai-viet-quat.jSpg',
                            'Bánh Tart Trái Cây Nhiệt Đới' => 'tart-trai-cay-nhiet-doi.jpg',
                            'Bánh Kem Bắp Non'      => 'kem-bap-non.jpg',
                            'Bánh Matcha Tiramisu Cake' => 'matcha-tiramisu.png',
                            'Bánh Kem Dâu Tây Đà Lạt' => 'kem-dau-tay-dalat.jpg',
                            'Bánh Su Kem' => 'Bánh Su Kem.jpg',
                            'Bánh Tiramisu' => 'Bánh Tiramisu.jpg',
                            'Bánh Macaron (Hộp 6 cái)' => 'Bánh Macaron (Hộp 6 cái).jpg',
                            'Bánh Crepe Sầu Riêng' => 'Bánh Crepe Sầu Riêng.jpg',
                            'Bánh Pancake Mật Ong' => 'Bánh Pancake Mật Ong.jpg',
                            'Bánh Cupcake Vani' => 'Bánh Cupcake Vani.jpg',
                            'Bánh Brownie Hạnh Nhân' => 'Bánh Brownie Hạnh Nhân.jpg',
                            'Bánh Donut Phủ Socola' => 'Bánh Donut Phủ Socola.jpg',
                            'Bánh Muffin Việt Quất' => 'banh-muffin-viet-quat.jpg',
                            'Bánh Cookies Bơ Sữa' => 'cookies-bo-sua.jpg',
                            'Bánh Bông Lan Trứng Muối' => 'banh-bong-lan-trung-muoi.jpg',
                            'Bánh Mì Chà Bông Cay'=> 'banh-mi-cha-bong-cay.jpg',
                            'Bánh Croissant Trứng Muối' => 'croissant-trung-muoi.jpg',
                            'Bánh Mì Bơ Tỏi' => 'banh-mi-bo-toi.jpg',
                            'Bánh Mì Xúc Xích Phô Mai' => 'banh-mi-xuc-xich-phon-mai.jpg',
                            'Bánh Hamburger Bò' => 'hamburger-bo.jpg',
                            'Bánh Gối Nhân Thịt Nấm' => 'banh-goi-nhan-thit-nam.jpg',
                            'Bánh Muffin Việt Quất' => 'banh-muffin-viet-quat.jpg', 
                            'Bánh Cookies Bơ Sữa' => 'cookies-bo-sua.jpg', 
                            'Bánh Bông Lan Trứng Muối' => 'banh-bong-lan-trung-muoi.jpg', 
                            'Bánh Mì Chà Bông Cay'=> 'banh-mi-cha-bong-cay.jpg', 
                            'Bánh Croissant Trứng Muối' => 'croissant-trung-muoi.jpg', 
                            'Bánh Mì Bơ Tỏi' => 'banh-mi-bo-toi.jpg', 
                            'Bánh Mì Xúc Xích Phô Mai' => 'banh-mi-xuc-xich-phon-mai.jpg', 
                            'Bánh Hamburger Bò' => 'hamburger-bo.jpg', 
                            'Bánh Gối Nhân Thịt Nấm' => 'banh-goi-nhan-thit-nam.jpg', 
                            'Bánh Mì Que Hải Phòng' => 'banh-mi-que-hai-phong.jpg'
                        ];
                $p_name = $row['product_name'];
                $file_name = isset($image_map[$p_name]) ? $image_map[$p_name] : 'product-1.jpg';
            ?>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="product-item d-flex flex-column bg-white rounded overflow-hidden h-100">
                        <div class="text-center p-4">
                            <div class="d-inline-block border border-primary rounded-pill px-3 mb-3">
                                <?php echo number_format($row['price'], 0, ',', '.'); ?>đ
                            </div>
                            <h4 class="mb-3"><?php echo $row['product_name']; ?></h4> 
                            
                        </div>
                        <div class="position-relative mt-auto">
                            <img class="img-fluid" src="assets/img/<?php echo $file_name; ?>" alt="<?php echo $row['product_name']; ?>">
                            <div class="product-overlay">
                                <a class="btn btn-lg-square btn-outline-light rounded-circle" href="javascript:void(0);" onclick="addToCart(event, <?php echo $row['product_id']; ?>)">
                                 <i class="fa fa-cart-plus text-primary"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                }
            } else {
                echo "<div class='col-12 text-center'>Menu is currently empty.</div>";
            }
            ?>

            <?php if ($total_pages > 1): ?>
            <div class="col-12 mt-5 text-center wow fadeInUp" data-wow-delay="0.1s">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mb-0">
                        
                        <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="index.php?page=product&p=<?php echo $current_page - 1; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>

                        <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($current_page == $i) ? 'active' : ''; ?>">
                            <a class="page-link" href="index.php?page=product&p=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>

                        <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="index.php?page=product&p=<?php echo $current_page + 1; ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                        
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
            </div>
    </div>
</div>

<?php include 'footer.php'; ?>