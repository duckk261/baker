import os
import re

file = 'views/home.php'

translations = [
    (r'>\s*The Best Bakery\s*<', '>Tiệm Bánh Ngon Nhất<'),
    (r'>\s*We Bake With Passion\s*<', '>Chúng Tôi Nướng Bánh Bằng Đam Mê<'),
    (r'>\s*Experience the rich flavors of our freshly baked goods, made with love and the finest ingredients.\s*<', '>Hãy trải nghiệm hương vị phong phú của những chiếc bánh mới ra lò, được làm bằng tình yêu và nguyên liệu hảo hạng nhất.<'),
    (r'>\s*Years Experience\s*<', '>Năm Kinh Nghiệm<'),
    (r'>\s*Happy Customers\s*<', '>Khách Hàng Hài Lòng<'),
    (r'>\s*Total Products\s*<', '>Tổng Sản Phẩm<'),
    (r'>\s*Total Orders\s*<', '>Tổng Đơn Hàng<'),
    (r'>\s*We Bake Every Item From The Core Of Our Hearts\s*<', '>Chúng Tôi Nướng Bánh Bằng Cả Trái Tim<'),
    (r'>\s*Tempor We pride ourselves on delivering the most delicious pastries, cakes, and breads. Every bite is a taste of perfection, crafted to bring joy to your everyday moments.\s*<', '>Chúng tôi tự hào mang đến những chiếc bánh ngọt, bánh nướng và bánh mì thơm ngon nhất. Mỗi miếng cắn đều là một hương vị hoàn hảo, được tạo ra để mang lại niềm vui cho những khoảnh khắc hàng ngày của bạn.<'),
    (r'>\s*Quality Products\s*<', '>Sản Phẩm Chất Lượng<'),
    (r'>\s*Custom Products\s*<', '>Bánh Đặt Theo Yêu Cầu<'),
    (r'>\s*Online Order\s*<', '>Đặt Hàng Trực Tuyến<'),
    (r'>\s*Home Delivery\s*<', '>Giao Hàng Tận Nơi<'),
    (r'>\s*The Best Bakery In Your City\s*<', '>Tiệm Bánh Ngon Nhất Thành Phố<'),
    (r'>\s*Order Now\s*<', '>Đặt Hàng Ngay<'),
    (r'>\s*Featured Products\s*<', '>Sản Phẩm Nổi Bật<'),
    (r'>\s*Explore Our Best Sellers\s*<', '>Khám Phá Các Sản Phẩm Bán Chạy Nhất<'),
    (r'>\s*Freshly baked daily with premium ingredients.\s*<', '>Nướng mới mỗi ngày với nguyên liệu cao cấp.<'),
    (r'>\s*View All Products\s*<', '>Xem Tất Cả Sản Phẩm<'),
    (r'>\s*Our Services\s*<', '>Dịch Vụ Của Chúng Tôi<'),
    (r'>\s*What Do We Offer For You\?\s*<', '>Chúng Tôi Mang Đến Cho Bạn Những Gì?<'),
    (r'>\s*We ensure the best quality and service for all your bakery needs.\s*<', '>Chúng tôi đảm bảo chất lượng và dịch vụ tốt nhất cho mọi nhu cầu về bánh của bạn.<'),
    (r'>\s*Our Team\s*<', '>Đội Ngũ Của Chúng Tôi<'),
    (r'>\s*We\'re Super Professional At Our Skills\s*<', '>Chúng Tôi Có Kỹ Năng Chuyên Nghiệp<'),
    (r'>\s*// Client\'s Review\s*<', '>// Đánh Giá Của Khách Hàng<'),
    (r'>\s*More Than 20000\+ Customers Trusted Us\s*<', '>Hơn 20.000 Khách Hàng Đã Tin Tưởng Chúng Tôi<'),
    (r'>\s*Client Name\s*<', '>Tên Khách Hàng<'),
    (r'>\s*Profession\s*<', '>Nghề Nghiệp<'),
    (r'>\s*Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit diam amet diam et eos. Clita erat ipsum et lorem et sit.\s*<', '>Bánh rất ngon và chất lượng phục vụ rất tuyệt vời. Tôi sẽ giới thiệu cho bạn bè và người thân đến mua bánh tại đây.<'),
    (r'>\s*Subscribe Our Newsletter\s*<', '>Đăng Ký Nhận Bản Tin<'),
    (r'>\s*SignUp\s*<', '>Đăng Ký<'),
    (r'placeholder="Your email"', 'placeholder="Email của bạn"'),
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
