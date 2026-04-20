<div class="container-fluid bg-dark text-light footer my-6 mb-0 py-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Address</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>123 Street, Hanoi, Vietnam</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+84 987 654 321</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@baker.com</p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-square btn-outline-light rounded-circle me-1" href=""><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-square btn-outline-light rounded-circle me-1" href=""><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-square btn-outline-light rounded-circle me-0" href=""><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Quick Links</h4>
                    <a class="btn btn-link" href="about.php">About Us</a>
                    <a class="btn btn-link" href="contact.php">Contact Us</a>
                    <a class="btn btn-link" href="product.php">Our Products</a>
                    <a class="btn btn-link" href="#">Support</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Opening Hours</h4>
                    <p class="mb-1">Mon - Fri</p>
                    <h6 class="text-light">09:00 AM - 09:00 PM</h6>
                    <p class="mb-1">Sat - Sun</p>
                    <h6 class="text-light">10:00 AM - 10:00 PM</h6>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Photo Gallery</h4>
                    <div class="row g-2">
                        <div class="col-4"><img class="img-fluid bg-light rounded p-1" src="img/product-1.jpg" alt="Product"></div>
                        <div class="col-4"><img class="img-fluid bg-light rounded p-1" src="img/product-2.jpg" alt="Product"></div>
                        <div class="col-4"><img class="img-fluid bg-light rounded p-1" src="img/product-3.jpg" alt="Product"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid copyright text-light py-4 wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; <a href="index.php">bông</a>, All Rights Reserved.
                </div>
            </div>
        </div>
    </div>

    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded-circle back-to-top"><i class="bi bi-arrow-up"></i></a>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <script src="js/main.js"></script>
    <script>
 function addToCart(event, productId) {
    event.preventDefault(); 

    // Gọi đến file xử lý trong thư mục api/actions
    fetch('api/add_to_cart.php?id=' + productId)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const badge = document.getElementById('cart-badge');
                if(badge) badge.innerText = data.cart_count;
                alert('Đã thêm bánh vào giỏ hàng!');
            } 
            else if (data.status === 'not_logged_in') {
                alert('Bạn cần đăng nhập để mua hàng!');
                window.location.href = 'login.php'; 
            }
            else {
                alert(data.message || 'Có lỗi xảy ra, vui lòng thử lại.');
            }
        })
        .catch(error => {
            console.error('Lỗi kết nối:', error);
        });
}
    </script>
</body>
</html>