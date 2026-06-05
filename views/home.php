<?php
require_once 'app/classes/Database.php';
$db = Database::getInstance();
require_once 'app/models/ProductModel.php';
require_once 'app/models/StatsModel.php';
include 'header.php';
$productModel = new ProductModel($db); 
$featured_products = $productModel->getFeaturedProducts(3);
$statsModel = new StatsModel($db);
$total_products = $statsModel->getTotalProducts();
$total_orders = $statsModel->getTotalOrders();
$total_customers = $statsModel->getTotalCustomers();
$years_experience = 5;
 ?>

    <!-- Carousel Start -->
    <div class="container-fluid p-0 pb-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="owl-carousel header-carousel position-relative">
            <div class="owl-carousel-item position-relative">
                <img class="img-fluid" src="assets/img/carousel-1.jpg" alt="">
                <div class="owl-carousel-inner">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-lg-8">
                                <p class="text-primary text-uppercase fw-bold mb-2">Tiệm Bánh Ngon Nhất</p>
                                <h1 class="display-1 text-light mb-4 animated slideInDown">Chúng Tôi Nướng Bánh Bằng Đam Mê</h1>
                                <p class="text-light fs-5 mb-4 pb-3">Hãy trải nghiệm hương vị phong phú của những chiếc bánh mới ra lò, được làm bằng tình yêu và nguyên liệu hảo hạng nhất.</p>
                                <a href="index.php?page=about" class="btn btn-primary rounded-pill py-3 px-5">Xem Thêm</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="owl-carousel-item position-relative">
                <img class="img-fluid" src="assets/img/carousel-2.jpg" alt="">
                <div class="owl-carousel-inner">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-lg-8">
                                <p class="text-primary text-uppercase fw-bold mb-2">Tiệm Bánh Ngon Nhất</p>
               
               
                                <h1 class="display-1 text-light mb-4 animated slideInDown">Chúng Tôi Nướng Bánh Bằng Đam Mê</h1>
                                <p class="text-light fs-5 mb-4 pb-3">Hãy trải nghiệm hương vị phong phú của những chiếc bánh mới ra lò, được làm bằng tình yêu và nguyên liệu hảo hạng nhất.</p>
                                <a href="index.php?page=about" class="btn btn-primary rounded-pill py-3 px-5">Xem Thêm</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel End -->


    <!-- Facts Start -->
    <div class="container-xxl py-6">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6 wow fadeIn" data-wow-delay="0.1s">
                    <div class="fact-item bg-light rounded text-center h-100 p-5">
                        <i class="fa fa-certificate fa-4x text-primary mb-4"></i>
                        <p class="mb-2">Năm Kinh Nghiệm</p>
                        <h1 class="display-5 mb-0" data-toggle="counter-up"><?php echo $years_experience; ?></h1>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 wow fadeIn" data-wow-delay="0.3s">
                    <div class="fact-item bg-light rounded text-center h-100 p-5">
                        <i class="fa fa-users fa-4x text-primary mb-4"></i>
                        <p class="mb-2">Khách Hàng Hài Lòng</p>
                        <h1 class="display-5 mb-0"><span data-toggle="counter-up">1200</span>+</h1>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 wow fadeIn" data-wow-delay="0.5s">
                    <div class="fact-item bg-light rounded text-center h-100 p-5">
                        <i class="fa fa-bread-slice fa-4x text-primary mb-4"></i>
                        <p class="mb-2">Tổng Sản Phẩm</p>
                        <h1 class="display-5 mb-0"><span data-toggle="counter-up">250</span>+</h1>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 wow fadeIn" data-wow-delay="0.7s">
                    <div class="fact-item bg-light rounded text-center h-100 p-5">
                        <i class="fa fa-cart-plus fa-4x text-primary mb-4"></i>
                        <p class="mb-2">Tổng Đơn Hàng</p>
                        <h1 class="display-5 mb-0"><span data-toggle="counter-up">5000</span>+</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Facts End -->


    <!-- About Start -->
    <div class="container-xxl py-6">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="row img-twice position-relative h-100">
                        <div class="col-6">
                            <img class="img-fluid rounded" src="assets/img/about-1.jpg" alt="">
                        </div>
                        <div class="col-6 align-self-end">
                            <img class="img-fluid rounded" src="assets/img/about-2.jpg" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="h-100">
                        <p class="text-primary text-uppercase mb-2">Về Chúng Tôi</p>
                        <h1 class="display-6 mb-4">Chúng Tôi Nướng Bánh Bằng Cả Trái Tim</h1>
                        <p>Chúng tôi tự hào mang đến những chiếc bánh ngọt, bánh nướng và bánh mì thơm ngon nhất. Mỗi miếng cắn đều là một hương vị hoàn hảo, được tạo ra để mang lại niềm vui cho những khoảnh khắc hàng ngày của bạn.</p>
                        <p>Chúng tôi tự hào mang đến những chiếc bánh ngọt, bánh nướng và bánh mì thơm ngon nhất. Mỗi miếng cắn đều là một hương vị hoàn hảo, được tạo ra để mang lại niềm vui cho những khoảnh khắc hàng ngày của bạn.</p>
                        <div class="row g-2 mb-4">
                            <div class="col-sm-6">
                                <i class="fa fa-check text-primary me-2"></i>Sản Phẩm Chất Lượng</div>
                            <div class="col-sm-6">
                                <i class="fa fa-check text-primary me-2"></i>Bánh Đặt Theo Yêu Cầu</div>
                            <div class="col-sm-6">
                                <i class="fa fa-check text-primary me-2"></i>Đặt Hàng Trực Tuyến</div>
                            <div class="col-sm-6">
                                <i class="fa fa-check text-primary me-2"></i>Giao Hàng Tận Nơi</div>
                        </div>
                        <a class="btn btn-primary rounded-pill py-3 px-5" href="index.php?page=about">Xem Thêm</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->


    <!-- Product Start -->
   <div class="container-xxl bg-light my-6 py-6 pt-0">
    <div class="container">
        <div class="bg-primary text-light rounded-bottom p-5 my-6 mt-0 wow fadeInUp" data-wow-delay="0.1s">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 text-light mb-0">Tiệm Bánh Ngon Nhất Thành Phố</h1>
                </div>
                <div class="col-lg-6 text-lg-end">
                    <div class="d-inline-flex align-items-center text-start">
                        <i class="fa fa-phone-alt fa-4x flex-shrink-0"></i>
                        <div class="ms-4">
                            <p class="fs-5 fw-bold mb-0">Đặt Hàng Ngay</p>
                            <p class="fs-1 fw-bold mb-0">0987 654 321</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <p class="text-primary text-uppercase mb-2">Sản Phẩm Nổi Bật</p>
            <h1 class="display-6 mb-4">Khám Phá Các Sản Phẩm Bán Chạy Nhất</h1>
        </div>
        
        <div class="row g-4">
         <?php 
            $delay = 0.1;
            $product_images = array(
                'mouse-chanh-day.jpg',
                'redvelet-cream.png',
                'dark-chocolate.jpg'
            );
            if ($featured_products && mysqli_num_rows($featured_products) > 0) {
                $count = 0;
                while(($row = mysqli_fetch_assoc($featured_products)) && $count < 3) {
                    $p_stock = $row['stock_quantity'] ?? 0;
                    $is_out_of_stock = ($p_stock <= 0);
            ?>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="<?php echo $delay; ?>s">
                    <div class="product-item d-flex flex-column bg-white rounded overflow-hidden h-100" <?php if($is_out_of_stock) echo 'style="opacity: 0.85;"'; ?>>
                        <div class="text-center p-4">
                            <div class="d-inline-block border border-primary rounded-pill px-3 mb-3">
                                <?php echo number_format($row['price'], 0, ',', '.'); ?>đ
                            </div>
                            <h4 class="mb-3"><?php echo $row['product_name']; ?></h4> 
                            <span>Nướng mới mỗi ngày với nguyên liệu cao cấp.</span>
                        </div>
                        <div class="position-relative mt-auto">
                            <img class="img-fluid" src="assets/img/<?php echo $product_images[$count]; ?>" alt="<?php echo $row['product_name']; ?>" <?php if($is_out_of_stock) echo 'style="filter: grayscale(100%);"'; ?>>
                            
                            <?php if ($is_out_of_stock): ?>
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(255,255,255,0.4); z-index: 2; pointer-events: none;">
                                    <span class="badge bg-danger text-white fs-5 px-4 py-2 shadow" style="border-radius: 8px; letter-spacing: 1px; font-weight: bold;">HẾT HÀNG</span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="product-overlay" <?php if($is_out_of_stock) echo 'style="z-index: 3;"'; ?>>
                                <?php if (!$is_out_of_stock): ?>
                                <a class="btn btn-lg-square btn-outline-light rounded-circle" href="javascript:void(0);" onclick="addToCart(event, <?php echo $row['product_id']; ?>)">
                                  <i class="fa fa-cart-plus text-primary"></i>
                                </a>
                                <?php else: ?>
                                <a class="btn btn-lg-square btn-outline-light rounded-circle disabled" href="javascript:void(0);" style="opacity: 0.5; pointer-events: none;">
                                  <i class="fa fa-cart-plus text-secondary"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                    $count++;
                    $delay += 0.2;
                }
            } else {
                echo "<div class='col-12 text-center'>Không có sản phẩm nổi bật nào.</div>";
            }
            ?>
            
            <div class="col-12 text-center mt-5 wow fadeInUp" data-wow-delay="0.1s">
                <a href="index.php?page=product" class="btn btn-primary rounded-pill py-3 px-5">Xem Tất Cả Sản Phẩm</a>
            </div>
            
        </div>
    </div>
</div>
    <!-- Product End -->


    <!-- Service Start -->
    <div class="container-xxl py-6">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <p class="text-primary text-uppercase mb-2">Dịch Vụ Của Chúng Tôi</p>
                    <h1 class="display-6 mb-4">Chúng Tôi Mang Đến Cho Bạn Những Gì?</h1>
                    <p class="mb-5">Chúng tôi tự hào mang đến những chiếc bánh ngọt, bánh nướng và bánh mì thơm ngon nhất. Mỗi miếng cắn đều là một hương vị hoàn hảo, được tạo ra để mang lại niềm vui cho những khoảnh khắc hàng ngày của bạn.</p>
                    <div class="row gy-5 gx-4">
                        <div class="col-sm-6 wow fadeIn" data-wow-delay="0.1s">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 btn-square bg-primary rounded-circle me-3">
                                    <i class="fa fa-bread-slice text-white"></i>
                                </div>
                                <h5 class="mb-0">Sản Phẩm Chất Lượng</h5>
                            </div>
                            <span>Chúng tôi đảm bảo chất lượng và dịch vụ tốt nhất cho mọi nhu cầu về bánh của bạn.</span>
                        </div>
                        <div class="col-sm-6 wow fadeIn" data-wow-delay="0.2s">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 btn-square bg-primary rounded-circle me-3">
                                    <i class="fa fa-birthday-cake text-white"></i>
                                </div>
                                <h5 class="mb-0">Bánh Đặt Theo Yêu Cầu</h5>
                            </div>
                            <span>Chúng tôi đảm bảo chất lượng và dịch vụ tốt nhất cho mọi nhu cầu về bánh của bạn.</span>
                        </div>
                        <div class="col-sm-6 wow fadeIn" data-wow-delay="0.3s">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 btn-square bg-primary rounded-circle me-3">
                                    <i class="fa fa-cart-plus text-white"></i>
                                </div>
                                <h5 class="mb-0">Đặt Hàng Trực Tuyến</h5>
                            </div>
                            <span>Chúng tôi đảm bảo chất lượng và dịch vụ tốt nhất cho mọi nhu cầu về bánh của bạn.</span>
                        </div>
                        <div class="col-sm-6 wow fadeIn" data-wow-delay="0.4s">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 btn-square bg-primary rounded-circle me-3">
                                    <i class="fa fa-truck text-white"></i>
                                </div>
                                <h5 class="mb-0">Giao Hàng Tận Nơi</h5>
                            </div>
                            <span>Chúng tôi đảm bảo chất lượng và dịch vụ tốt nhất cho mọi nhu cầu về bánh của bạn.</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="row img-twice position-relative h-100">
                        <div class="col-6">
                            <img class="img-fluid rounded" src="assets/img/service-1.jpg" alt="">
                        </div>
                        <div class="col-6 align-self-end">
                            <img class="img-fluid rounded" src="assets/img/service-2.jpg" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Service End -->


    <!-- Team Start -->
    <div class="container-xxl py-6">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
                <p class="text-primary text-uppercase mb-2">Đội Ngũ Của Chúng Tôi</p>
                <h1 class="display-6 mb-4">Chúng Tôi Có Kỹ Năng Chuyên Nghiệp</h1>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="team-item text-center rounded overflow-hidden">
                        <img class="img-fluid" src="assets/img/thuha.jpg" alt="">
                        <div class="team-text">
                            <div class="team-title">
                                <h5>Phạm Thu Hà</h5>
                                <span>Bếp trưởng</span>
                            </div>
                            <div class="team-social">
                                <a class="btn btn-square btn-light rounded-circle" href="https://web.facebook.com/profile.php?id=61584104759477" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square btn-light rounded-circle" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-square btn-light rounded-circle" href=""><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="team-item text-center rounded overflow-hidden">
                        <img class="img-fluid" src="assets/img/minhthu.jpg" alt="">
                        <div class="team-text">
                            <div class="team-title">
                                <h5>Hoàng Minh Thu</h5>
                                <span>Giám đốc Marketing</span>
                            </div>
                            <div class="team-social">
                                <a class="btn btn-square btn-light rounded-circle" href="https://web.facebook.com/profile.php?id=61584104759477" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square btn-light rounded-circle" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-square btn-light rounded-circle" href=""><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="team-item text-center rounded overflow-hidden">
                        <img class="img-fluid" src="assets/img/minhthu.jpg" alt="">
                        <div class="team-text">
                            <div class="team-title">
                                <h5>Hoàng Anh Đức</h5>
                                <span>Phụ bếp</span>
                            </div>
                            <div class="team-social">
                                <a class="btn btn-square btn-light rounded-circle" href="https://web.facebook.com/profile.php?id=61584104759477" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square btn-light rounded-circle" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-square btn-light rounded-circle" href=""><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.7s">
                    <div class="team-item text-center rounded overflow-hidden">
                        <img class="img-fluid" src="assets/img/team-4.jpg" alt="">
                        <div class="team-text">
                            <div class="team-title">
                                <h5>Lôi Thị Hương</h5>
                                <span>Quản lý cửa hàng</span>
                            </div>
                            <div class="team-social">
                                <a class="btn btn-square btn-light rounded-circle" href="https://web.facebook.com/profile.php?id=61584104759477" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square btn-light rounded-circle" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-square btn-light rounded-circle" href=""><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Team End -->


    <!-- Testimonial Start -->
    <div class="container-xxl bg-light my-6 py-6 pb-0">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
                <p class="text-primary text-uppercase mb-2">// Đánh Giá Của Khách Hàng</p>
                <h1 class="display-6 mb-4">Hơn 20.000 Khách Hàng Đã Tin Tưởng Chúng Tôi</h1>
            </div>
            <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.1s">
                <?php
                $reviews_query = mysqli_query($db, "
                    SELECT r.comment, r.rating, c.full_name as reviewer_name
                    FROM reviews r 
                    LEFT JOIN customers c ON r.account_id = c.customer_id
                    WHERE r.status = 1 AND r.comment != '' AND r.comment IS NOT NULL
                    ORDER BY r.created_at DESC 
                    LIMIT 6
                ");

                if ($reviews_query && mysqli_num_rows($reviews_query) > 0) {
                    $i = 1;
                    while ($rv = mysqli_fetch_assoc($reviews_query)) {
                        $name = !empty($rv['reviewer_name']) ? htmlspecialchars($rv['reviewer_name']) : 'Khách Hàng';
                        $comment = htmlspecialchars($rv['comment']);
                        $avatar_num = ($i % 4) == 0 ? 4 : ($i % 4);
                ?>
                <div class="testimonial-item bg-white rounded p-4">
                    <div class="d-flex align-items-center mb-4">
                        <img class="flex-shrink-0 rounded-circle border p-1" src="assets/img/testimonial-<?php echo $avatar_num; ?>.jpg" alt="">
                        <div class="ms-4">
                            <h5 class="mb-1"><?php echo $name; ?></h5>
                            <span>Khách Hàng Thân Thiết</span>
                        </div>
                    </div>
                    <p class="mb-0">"<?php echo $comment; ?>"</p>
                </div>
                <?php 
                        $i++;
                    } 
                } else {
                    echo "<div class='text-center w-100 py-4'>Chưa có đánh giá nào.</div>";
                }
                ?>
            </div>

        </div>
    </div>
    <!-- Testimonial End -->


<?php include 'footer.php'; ?>