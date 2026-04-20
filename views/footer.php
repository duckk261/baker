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
        <div class="container text-center">
            &copy; <a href="index.php?page=home">bông</a>, All Rights Reserved.
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="assets/lib/wow/wow.min.js"></script>
    <script src="assets/lib/easing/easing.min.js"></script>
    <script src="assets/lib/waypoints/waypoints.min.js"></script>
    <script src="assets/lib/counterup/counterup.min.js"></script>
    <script src="assets/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="assets/js/main.js"></script>

    <script>
    function addToCart(event, productId) {
        event.preventDefault(); 
        // Đường dẫn chuẩn MVC
        fetch('index.php?page=cart&action=add&id=' + productId)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const badge = document.getElementById('cart-badge');
                    if(badge) badge.innerText = data.cart_count;
                    alert('Đã thêm bánh vào giỏ hàng!');
                } else if (data.status === 'not_logged_in') {
                    alert('Bạn cần đăng nhập để mua hàng!');
                    window.location.href = 'index.php?page=login'; 
                } else {
                    alert(data.message || 'Có lỗi xảy ra.');
                }
            });
    }
    </script>
</body>
</html>