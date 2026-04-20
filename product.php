<?php include 'header.php'; ?>

<div class="container-xxl bg-light py-6 my-6 mt-0">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <p class="text-primary text-uppercase mb-2">Our Menu</p>
            <h1 class="display-6 mb-4">Explore All Our Bakery Products</h1>
        </div>
        
        <div class="row g-4">
            <?php 
            // KHÔNG CÓ LIMIT -> LẤY TẤT CẢ SẢN PHẨM TỪ SQL
            $sql = "SELECT * FROM San_Pham"; 
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
            ?>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="product-item d-flex flex-column bg-white rounded overflow-hidden h-100">
                        <div class="text-center p-4">
                            <div class="d-inline-block border border-primary rounded-pill px-3 mb-3">
                                <?php echo number_format($row['Gia'], 0, ',', '.'); ?>đ
                            </div>
                            <h4 class="mb-3"><?php echo $row['Ten_SP']; ?></h4> 
                            <span>Freshly baked daily with premium ingredients.</span>
                        </div>
                        <div class="position-relative mt-auto">
                            <img class="img-fluid" src="img/product-1.jpg" alt="">
                            <div class="product-overlay">
                                <a class="btn btn-lg-square btn-outline-light rounded-circle" href="javascript:void(0);" onclick="addToCart(event, <?php echo $row['Ma_SP']; ?>)">
                                 <i class="fa fa-cart-plus text-primary"></i>
                                    </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                } // Kết thúc vòng lặp
            } else {
                echo "<div class='col-12 text-center'>Menu is currently empty.</div>";
            }
            ?>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>