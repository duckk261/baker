<?php 
// 1. LẤY DANH SÁCH YÊU THÍCH
$user_favorites = [];
if (isset($_SESSION['account_id'])) {
    $acc_id = $_SESSION['account_id'];
    $fav_query = mysqli_query($db, "SELECT product_id FROM favorites WHERE account_id = '$acc_id'");
    while ($f = mysqli_fetch_assoc($fav_query)) {
        $user_favorites[] = $f['product_id'];
    }
}

require_once 'app/models/ProductModel.php';
require_once 'app/classes/Database.php';
$db = Database::getInstance();
// Vẫn giữ khai báo Model để không lỗi cấu trúc MVC của ông
$productModel = new ProductModel($db); 

$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$cat_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$rating = isset($_GET['rating']) ? $_GET['rating'] : 'all'; // Đổi thành chuỗi (string) để chứa khoảng
$is_search = !empty($search_term);

// 3. THIẾT LẬP PHÂN TRANG
$limit = 6; 
$page_num = isset($_GET['p']) ? (int)$_GET['p'] : 1; 
if ($page_num < 1) $page_num = 1;
$offset = ($page_num - 1) * $limit;

$cat_query = @mysqli_query($db, "SELECT * FROM categories");
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
$total_pages = ceil($total_products / $limit);

// Truy vấn lấy dữ liệu Bánh + Kèm tính trung bình cột rating
$data_sql = "SELECT p.*, AVG(r.rating) as avg_rating FROM products p LEFT JOIN reviews r ON p.product_id = r.product_id WHERE $where_sql GROUP BY p.product_id $having_clause ORDER BY p.product_id DESC LIMIT $limit OFFSET $offset";
$all_products = @mysqli_query($db, $data_sql);

// Lưu lại URL
$query_string = "";
if ($is_search) $query_string .= "&search=" . urlencode($search_term);
if ($cat_id > 0) $query_string .= "&category=$cat_id";
if ($rating !== 'all') $query_string .= "&rating=$rating";
include 'header.php'; 
?>

<div class="container-xxl bg-light py-6 my-6 mt-0">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <p class="text-primary text-uppercase mb-2">Our Menu</p>
            <h1 class="display-6 mb-4">
                <?php echo $is_search ? 'Kết quả tìm kiếm: "' . htmlspecialchars($search_term) . '"' : 'Explore All Our Bakery Products'; ?>
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
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: #c4a16b;">Lọc theo Danh Mục</label>
<select name="category" class="form-select" style="border-color: #c4a16b; cursor: pointer;" onchange="filterProductsAJAX()">
    <option value="0">🎂 Tất cả danh mục</option>
    <?php 
    // TRUY VẤN TRỰC TIẾP KHÔNG ĐIỀU KIỆN ĐỂ TRÁNH TRẮNG DROPDOWN
    $all_cats = mysqli_query($db, "SELECT * FROM categories"); 
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

                      <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: #c4a16b;">Lọc theo Đánh Giá</label>
<select name="rating" class="form-select" style="border-color: #c4a16b; cursor: pointer;" onchange="filterProductsAJAX()">                                <option value="all">⭐ Tất cả đánh giá</option>
                                <option value="5" <?php echo ($rating === '5') ? 'selected' : ''; ?>>⭐⭐⭐⭐⭐ (5 Sao Tuyệt đối)</option>
                                <option value="4-5" <?php echo ($rating === '4-5') ? 'selected' : ''; ?>>⭐⭐⭐⭐ (Từ 4 đến dưới 5 Sao)</option>
                                <option value="3-4" <?php echo ($rating === '3-4') ? 'selected' : ''; ?>>⭐⭐⭐ (Từ 3 đến dưới 4 Sao)</option>
                                <option value="1-3" <?php echo ($rating === '1-3') ? 'selected' : ''; ?>>⭐⭐ (Dưới 3 Sao)</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="row g-4" id="product-list-container" style="transition: 0.3s;">
           <?php 
            if ($total_products > 0) {
                while($row = mysqli_fetch_assoc($all_products)) {
                    $p_name = $row['product_name'] ?? 'Bánh mới';
                    $p_price = $row['price'] ?? 0;
                    $file_name = !empty($row['image']) ? $row['image'] : 'default.jpg';
            ?>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="product-item d-flex flex-column bg-white rounded overflow-hidden h-100">
                        <div class="text-center p-4">
                            <div class="d-inline-block border border-primary rounded-pill px-3 mb-3">
                                <?php echo number_format($p_price, 0, ',', '.'); ?>đ
                            </div>
                            <h4 class="mb-3"><?php echo $p_name; ?></h4> 
                            <span>Freshly baked daily with premium ingredients.</span>
                        </div>

                        <div class="position-relative mt-auto">
                            <img class="img-fluid w-100" src="assets/img/<?php echo $file_name; ?>" alt="<?php echo $p_name; ?>" style="height: 250px; object-fit: cover;" onerror="this.onerror=null; this.src='https://placehold.co/250x250?text=No+Image';">
                            
                         <div class="product-overlay d-flex justify-content-center align-items-center">
                                <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="javascript:void(0);" onclick="addToCart(event, <?php echo $row['product_id']; ?>)" title="Add to Cart">
                                 <i class="fa fa-cart-plus text-primary"></i>
                                </a>
                                
                                <?php 
                                $is_favorite = in_array($row['product_id'], $user_favorites);
                                $heart_icon = $is_favorite ? 'fas fa-heart' : 'far fa-heart';
                                ?>
                               <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="javascript:void(0);" onclick="toggleFavorite(event, <?php echo $row['product_id']; ?>, this)" title="Wishlist">
                                 <i class="<?php echo $heart_icon; ?> text-primary"></i>
                                </a>

                                <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="index.php?page=product_detail&id=<?php echo $row['product_id']; ?>" title="View Details">
                                 <i class="fa fa-eye text-info"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                }
            } 
            ?>

            <?php if ($total_pages > 1): ?>
            <div class="col-12 mt-5 text-center wow fadeInUp" data-wow-delay="0.1s">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mb-0">
                        
                        <li class="page-item <?php echo ($page_num <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="index.php?page=product<?php echo $query_string; ?>&p=<?php echo $page_num - 1; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>

                        <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($page_num == $i) ? 'active' : ''; ?>">
                            <a class="page-link" href="index.php?page=product<?php echo $query_string; ?>&p=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>

                        <li class="page-item <?php echo ($page_num >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="index.php?page=product<?php echo $query_string; ?>&p=<?php echo $page_num + 1; ?>" aria-label="Next">
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
<script>
// Hàm xử lý gọi dữ liệu ngầm không cần F5
function loadDataAJAX(url) {
    let container = document.getElementById('product-list-container');
    
    // Hiệu ứng mờ đi lúc đang tải dữ liệu
    container.style.opacity = '0.3'; 

    fetch(url)
    .then(response => response.text())
    .then(html => {
        // Biến cục HTML trả về thành đối tượng DOM để dễ bóc tách
        let parser = new DOMParser();
        let doc = parser.parseFromString(html, 'text/html');
        
        // Bóc đúng khu vực danh sách bánh
        let newContent = doc.getElementById('product-list-container');
        
        // Xóa các class hiệu ứng (wow) đi để bánh hiện ra luôn, không bị ẩn chờ cuộn chuột
        newContent.querySelectorAll('.wow').forEach(el => {
            el.classList.remove('wow', 'fadeInUp');
            el.style.visibility = 'visible';
            el.style.animationName = 'none';
        });

        // Đắp dữ liệu mới vào
        container.innerHTML = newContent.innerHTML;
        
        // Hiện rõ lại
        container.style.opacity = '1';

        // Đổi luôn URL trên trình duyệt để khách F5 hoặc gửi link cho bạn bè vẫn không bị mất bộ lọc
        window.history.pushState({}, '', url);
    })
    .catch(err => {
        console.error('Lỗi lọc AJAX:', err);
        container.style.opacity = '1';
    });
}

function filterProductsAJAX() {
    let catId = document.querySelector('select[name="category"]').value;
    let rating = document.querySelector('select[name="rating"]').value;
    let search = document.querySelector('input[name="search"]') ? document.querySelector('input[name="search"]').value : '';

    // URL này phải khớp đúng với các tham số mà PHP đang nhận ở trên
    let url = `index.php?page=product&category=${catId}&rating=${rating}`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    
    // Gọi hàm load dữ liệu
    loadDataAJAX(url);
}
</script>
<?php include 'footer.php'; ?>