<div class="container-fluid bg-dark text-light footer my-6 mb-0 py-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Địa Chỉ</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>12 Chùa Bộc, Đống Đa, Hà Nội</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+84 987 654 321</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@baker.com</p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-square btn-outline-light rounded-circle me-1" href=""><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-square btn-outline-light rounded-circle me-1" href="https://web.facebook.com/profile.php?id=61584104759477" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-square btn-outline-light rounded-circle me-0" href=""><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Liên Kết Nhanh</h4>
                    <a class="btn btn-link" href="index.php?page=about">Về Chúng Tôi</a>
                    <a class="btn btn-link" href="index.php?page=contact">Liên Hệ</a>
                    <a class="btn btn-link" href="index.php?page=product">Sản Phẩm Của Chúng Tôi</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Giờ Mở Cửa</h4>
                    <p class="mb-1">Thứ 2 - Thứ 6</p>
                    <h6 class="text-light">09:00 AM - 09:00 PM</h6>
                    <p class="mb-1">Thứ 7 - Chủ Nhật</p>
                    <h6 class="text-light">10:00 AM - 10:00 PM</h6>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Thư Viện Ảnh</h4>
                    <div class="row g-2">
                        <div class="col-4"><img class="img-fluid bg-light rounded p-1" src="assets/img/product-1.jpg" alt="Product"></div>
                        <div class="col-4"><img class="img-fluid bg-light rounded p-1" src="assets/img/product-2.jpg" alt="Product"></div>
                        <div class="col-4"><img class="img-fluid bg-light rounded p-1" src="assets/img/product-3.jpg" alt="Product"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <div class="container-fluid copyright text-light py-4 wow fadeIn" data-wow-delay="0.1s">
        <div class="container text-center">
            &copy; <a href="index.php?page=home">bông</a>, Mọi Bản Quyền Được Bảo Lưu.
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
                    Swal.fire({title: 'Thông báo', text: 'Đã thêm bánh vào giỏ hàng!', confirmButtonColor: '#c4a16b', icon: 'info'});
                } else if (data.status === 'not_logged_in') {
                    Swal.fire({title: 'Thông báo', text: 'Bạn cần đăng nhập để mua hàng!', confirmButtonColor: '#c4a16b', icon: 'info'}).then(() => {
                        window.location.href = 'index.php?page=login'; 
                    });
                } else {
                    Swal.fire({title: 'Thông báo', text: data.message || 'Có lỗi xảy ra.', confirmButtonColor: '#c4a16b', icon: 'info'});
                }
            });
    }
    </script>
    <script>
function toggleFavorite(event, productId, btnElement) {
    event.preventDefault(); 
    
    fetch('index.php?action=toggle_favorite&id=' + productId)
        .then(response => response.text()) // Đổi tạm thành text để bắt lỗi
        .then(text => {
            try {
                let data = JSON.parse(text); // Thử dịch sang JSON
                if (data.status === 'error') {
                    Swal.fire({title: 'Thông báo', text: data.message, confirmButtonColor: '#c4a16b', icon: 'info'});
                    window.location.href = 'index.php?page=login';
                } else {
                    Swal.fire({title: 'Thông báo', text: data.message, confirmButtonColor: '#c4a16b', icon: 'info'});
                    let icon = btnElement.querySelector('i');
                    if (data.status === 'added') {
                        icon.classList.remove('far'); 
                        icon.classList.add('fas');    
                    } else if (data.status === 'removed') {
                        icon.classList.remove('fas'); 
                        icon.classList.add('far');    
                    }
                }
            } catch (err) {
                // Nếu PHP trả về cục HTML lỗi chứ không phải JSON, nó sẽ báo ở đây
                console.error("Lỗi dữ liệu PHP trả về:", text);
                Swal.fire({title: 'Thông báo', text: "Hệ thống xử lý thất bại! Mở F12 sang tab Console để xem lỗi.", confirmButtonColor: '#c4a16b', icon: 'info'});
            }
        })
        .catch(error => {
            console.error('Lỗi mạng/Fetch:', error);
            Swal.fire({title: 'Thông báo', text: "Lỗi kết nối mạng, vui lòng thử lại!", confirmButtonColor: '#c4a16b', icon: 'info'});
        });
}
</script>
</body>
</html>