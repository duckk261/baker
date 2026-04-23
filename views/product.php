<?php 
require_once 'app/models/ProductModel.php';
$productModel = new ProductModel($db);


$limit = 6; 
$current_page = isset($_GET['p']) ? (int)$_GET['p'] : 1; 
if ($current_page < 1) $current_page = 1;

$offset = ($current_page - 1) * $limit;
$total_products = $productModel->getTotalProducts();
$total_pages = ceil($total_products / $limit);

$all_products = $productModel->getProductsPaginated($limit, $offset);

include 'header.php'; 
?>

<div class="container-xxl bg-light py-6 my-6 mt-0">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <p class="text-primary text-uppercase mb-2">Our Menu</p>
            <h1 class="display-6 mb-4">Explore All Our Bakery Products</h1>
        </div>
        
        <div class="row g-4">
            <?php 
            if ($all_products && mysqli_num_rows($all_products) > 0) {
                while($row = mysqli_fetch_assoc($all_products)) {
                $image_map = [
                            'Bánh Mousse Chanh Dây' => 'mouse-chanh-day.jpg',
                            'Bánh Red Velvet Cream' => 'redvelet-cream.png',
                            'Bánh Dark Chocolate'   => 'dark-chocolate.jpg',
                            'Bánh Kem Phô Mai Việt Quất' => 'kem-pho-mai-viet-quat.jpg',
                            'Bánh Tart Trái Cây Nhiệt Đới' => 'tart-trai-cay-nhiet-doi.jpg',
                            'Bánh Kem Bắp Non'      => 'kem-bap-non.jpg',
                            'Bánh Matcha Tiramisu Cake' => 'matcha-tiramisu.png',
                            'Bánh Kem Dâu Tây Đà Lạt' => 'kem-dau-tay-dalat.jpg'
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
                            <span>Freshly baked daily with premium ingredients.</span>
                        </div>


                        <div class="position-relative mt-auto">
                            <img class="img-fluid" src="assets/img/<?php echo $file_name; ?>" alt="">
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