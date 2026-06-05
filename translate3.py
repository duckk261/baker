import os
import re

file = 'views/about.php'

translations = [
    (r'>\s*We Bake Every Item From The Core Of Our Hearts\s*<', '>Chúng Tôi Nướng Bánh Bằng Cả Trái Tim<'),
    (r'>\s*Tempor We pride ourselves on delivering the most delicious pastries, cakes, and breads. Every bite is a taste of perfection, crafted to bring joy to your everyday moments.\s*<', '>Chúng tôi tự hào mang đến những chiếc bánh ngọt, bánh nướng và bánh mì thơm ngon nhất. Mỗi miếng cắn đều là một hương vị hoàn hảo, được tạo ra để mang lại niềm vui cho những khoảnh khắc hàng ngày của bạn.<'),
    (r'>\s*Quality Products\s*<', '>Sản Phẩm Chất Lượng<'),
    (r'>\s*Custom Products\s*<', '>Bánh Đặt Theo Yêu Cầu<'),
    (r'>\s*Online Order\s*<', '>Đặt Hàng Trực Tuyến<'),
    (r'>\s*Home Delivery\s*<', '>Giao Hàng Tận Nơi<'),
    (r'>\s*Our Team\s*<', '>Đội Ngũ Của Chúng Tôi<'),
    (r'>\s*We\'re Super Professional At Our Skills\s*<', '>Chúng Tôi Có Kỹ Năng Chuyên Nghiệp<'),
    (r'>\s*Full Name\s*<', '>Tên Nhân Viên<'),
    (r'>\s*Designation\s*<', '>Chức Vụ<'),
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
