import os
import re

file = 'views/checkout.php'

translations = [
    (r'Please login to checkout!', 'Vui lòng đăng nhập để thanh toán!'),
    (r'Your cart is empty!', 'Giỏ hàng của bạn đang trống!'),
    (r'>\s*Complete Your Order\s*<', '>Hoàn Tất Đơn Hàng<'),
    (r'>\s*Shipping Address\s*<', '>Địa Chỉ Giao Hàng<'),
    (r'>\s*Full Name\s*<', '>Họ và Tên<'),
    (r'>\s*Email Address \(Optional\)\s*<', '>Địa Chỉ Email (Tùy chọn)<'),
    (r'>\s*Phone Number\s*<', '>Số Điện Thoại<'),
    (r'>\s*Full Shipping Address\s*<', '>Địa Chỉ Giao Hàng Chi Tiết<'),
    (r'>\s*Order Notes \(Optional\)\s*<', '>Ghi Chú Đơn Hàng (Tùy chọn)<'),
    (r'placeholder="Notes"', 'placeholder="Ghi chú"'),
    (r'>\s*Payment Method\s*<', '>Phương Thức Thanh Toán<'),
    (r'>\s*Cash on Delivery \(COD\)\s*<', '>Thanh Toán Khi Nhận Hàng (COD)<'),
    (r'>\s*Bank Transfer\s*<', '>Chuyển Khoản Ngân Hàng<'),
    (r'>\s*Your Order\s*<', '>Đơn Hàng Của Bạn<'),
    (r'>\s*Product\s*<', '>Sản Phẩm<'),
    (r'>\s*Shipping\s*<', '>Phí Vận Chuyển<'),
    (r'>\s*Total Price\s*<', '>Tổng Tiền<'),
    (r'>\s*Place Order\s*<', '>Đặt Hàng<'),
    (r'>\s*Back to Cart\s*<', '>Quay Lại Giỏ Hàng<'),
    (r'submitBtn.innerHTML = "Place Order";', 'submitBtn.innerHTML = "Đặt Hàng";'),
]

try:
    with open(file, 'r', encoding='utf-8') as f:
        content = f.read()
        
    for eng, vie in translations:
        content = re.sub(eng, vie, content, flags=re.IGNORECASE)
        
    with open(file, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f"Updated: {file}")
except Exception as e:
    print(f"Error reading {file}: {e}")
