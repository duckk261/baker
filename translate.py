import os
import re

directories = ['admin/views', 'views', 'admin']
files_to_process = []

for d in directories:
    for root, dirs, files in os.walk(d):
        for file in files:
            if file.endswith('.php'):
                files_to_process.append(os.path.join(root, file))

translations = [
    # Sidebar & Menu
    (r'>\s*Dashboard\s*<', '>Bảng Điều Khiển<'),
    (r'>\s*Products\s*<', '>Sản Phẩm<'),
    (r'>\s*Categories\s*<', '>Danh Mục<'),
    (r'>\s*Orders\s*<', '>Đơn Hàng<'),
    (r'>\s*Accounts\s*<', '>Tài Khoản<'),
    (r'>\s*Reviews\s*<', '>Đánh Giá<'),
    (r'>\s*Contacts\s*<', '>Liên Hệ<'),
    (r'>\s*Logout\s*<', '>Đăng Xuất<'),
    
    # Headers
    (r'>\s*Product Management\s*<', '>Quản Lý Sản Phẩm<'),
    (r'>\s*Order Management\s*<', '>Quản Lý Đơn Hàng<'),
    (r'>\s*Category Management\s*<', '>Quản Lý Danh Mục<'),
    (r'>\s*Account Management\s*<', '>Quản Lý Tài Khoản<'),
    (r'>\s*Review Management\s*<', '>Quản Lý Đánh Giá<'),
    (r'>\s*Contact Management\s*<', '>Quản Lý Liên Hệ<'),
    
    # Buttons & Actions
    (r'>\s*Add Product\s*<', '>Thêm Sản Phẩm<'),
    (r'>\s*New Product\s*<', '>Sản Phẩm Mới<'),
    (r'>\s*Edit Product\s*<', '>Sửa Sản Phẩm<'),
    (r'>\s*Add Category\s*<', '>Thêm Danh Mục<'),
    (r'>\s*Edit Category\s*<', '>Sửa Danh Mục<'),
    (r'>\s*Search\s*<', '>Tìm Kiếm<'),
    (r'>\s*Clear Filter\s*<', '>Xóa Bộ Lọc<'),
    (r'>\s*View Details\s*<', '>Xem Chi Tiết<'),
    (r'>\s*Approve\s*<', '>Duyệt<'),
    (r'>\s*Cancel\s*<', '>Hủy<'),
    (r'>\s*Complete\s*<', '>Hoàn Tất<'),
    (r'>\s*Delete\s*<', '>Xóa<'),
    (r'>\s*Edit\s*<', '>Sửa<'),
    (r'>\s*Back\s*<', '>Quay Lại<'),
    (r'>\s*Save Changes\s*<', '>Lưu Thay Đổi<'),
    (r'>\s*SELECT BREAD IMAGE\s*<', '>CHỌN ẢNH BÁNH<'),
    
    # Table Headers
    (r'>\s*Order ID\s*<', '>Mã Đơn<'),
    (r'>\s*Total Amount\s*<', '>Tổng Tiền<'),
    (r'>\s*Status\s*<', '>Trạng Thái<'),
    (r'>\s*Actions\s*<', '>Thao Tác<'),
    (r'>\s*Product Name\s*<', '>Tên Sản Phẩm<'),
    (r'>\s*Price\s*<', '>Giá<'),
    (r'>\s*Stock\s*<', '>Tồn Kho<'),
    (r'>\s*Action\s*<', '>Thao Tác<'),
    (r'>\s*Image\s*<', '>Hình Ảnh<'),
    (r'>\s*Category Name\s*<', '>Tên Danh Mục<'),
    (r'>\s*Username\s*<', '>Tên Đăng Nhập<'),
    (r'>\s*Email\s*<', '>Email<'),
    (r'>\s*Role\s*<', '>Vai Trò<'),
    
    # Forms
    (r'>\s*Selling Price\s*\(VNĐ\)\s*<', '>Giá Bán (VNĐ)<'),
    (r'>\s*Initial Stock\s*<', '>Tồn Kho Ban Đầu<'),
    (r'>\s*Visible\s*<', '>Hiển thị<'),
    (r'>\s*Hidden\s*<', '>Ẩn<'),
    (r'>\s*Product Description\s*<', '>Mô tả Sản Phẩm<'),
    (r'>\s*Select Category\s*<', '>Chọn Danh Mục<'),
    (r'>\s*-- Select Category --\s*<', '>-- Chọn Danh Mục --<'),
    
    # Statuses
    (r'>\s*In Stock:\s*', '>Tồn kho: '),
    (r'>\s*Out of Stock\s*<', '>Hết Hàng<'),
    (r'>\s*No products found.\s*<', '>Không tìm thấy sản phẩm.<'),
    (r'>\s*No orders found.\s*<', '>Không tìm thấy đơn hàng.<'),
    (r'>\s*No categories found.\s*<', '>Không tìm thấy danh mục.<'),
    (r'>\s*No accounts found.\s*<', '>Không tìm thấy tài khoản.<'),
    
    # Frontend
    (r'>\s*Home\s*<', '>Trang Chủ<'),
    (r'>\s*About Us\s*<', '>Về Chúng Tôi<'),
    (r'>\s*Contact Us\s*<', '>Liên Hệ<'),
    (r'>\s*Cart\s*<', '>Giỏ Hàng<'),
    (r'>\s*Wishlist\s*<', '>Yêu Thích<'),
    (r'>\s*Login\s*<', '>Đăng Nhập<'),
    (r'>\s*Register\s*<', '>Đăng Ký<'),
    (r'>\s*My Profile\s*<', '>Hồ Sơ Của Tôi<'),
    (r'>\s*Order History\s*<', '>Lịch Sử Đơn Hàng<'),
    (r'>\s*Add to Cart\s*<', '>Thêm vào Giỏ<'),
    (r'>\s*Read More\s*<', '>Xem Thêm<'),
    (r'>\s*Checkout\s*<', '>Thanh Toán<'),
    (r'>\s*Subtotal\s*<', '>Tạm Tính<'),
    (r'>\s*Total\s*<', '>Tổng Cộng<'),
    (r'>\s*Quantity\s*<', '>Số Lượng<'),
    (r'>\s*Remove\s*<', '>Xóa<'),
    (r'>\s*Continue Shopping\s*<', '>Tiếp Tục Mua Sắm<'),
]

for file in set(files_to_process):
    try:
        with open(file, 'r', encoding='utf-8') as f:
            content = f.read()
            
        new_content = content
        for eng, vie in translations:
            new_content = re.sub(eng, vie, new_content, flags=re.IGNORECASE)
            
        if new_content != content:
            with open(file, 'w', encoding='utf-8') as f:
                f.write(new_content)
                print(f"Updated: {file}")
    except Exception as e:
        print(f"Error reading {file}: {e}")

print("Translation replacements completed.")
