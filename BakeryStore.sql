-- 1. CREATE DATABASE
CREATE DATABASE IF NOT EXISTS BakeryStore;
USE BakeryStore;
-- 2. CREATE TABLES 

-- Categories (Loại hàng)
CREATE TABLE Categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL,
    description TEXT
) ENGINE=InnoDB;

-- Products (Sản phẩm)
CREATE TABLE Products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    product_name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) UNSIGNED NOT NULL, 
    stock_quantity INT UNSIGNED NOT NULL DEFAULT 0, 
    CONSTRAINT fk_product_category FOREIGN KEY (category_id) REFERENCES Categories(category_id) ON DELETE SET NULL,
    CONSTRAINT chk_positive_price CHECK (price >= 0),
    CONSTRAINT chk_positive_stock CHECK (stock_quantity >= 0)
) ENGINE=InnoDB;

-- Customers (Khách hàng)
CREATE TABLE Customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    address TEXT,
    phone_number VARCHAR(15),
    email VARCHAR(100) UNIQUE
) ENGINE=InnoDB;

-- Accounts (Tài khoản)
CREATE TABLE Accounts (
    username VARCHAR(50) PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    customer_id INT,
    role ENUM('Admin', 'User') DEFAULT 'User',
    CONSTRAINT fk_account_customer FOREIGN KEY (customer_id) REFERENCES Customers(customer_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Orders 
CREATE TABLE Orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    subtotal DECIMAL(10, 2) UNSIGNED NOT NULL,
    shipping_fee DECIMAL(10, 2) UNSIGNED DEFAULT 0,
    total_amount DECIMAL(10, 2) UNSIGNED NOT NULL,
    status ENUM('Cho_duyet', 'Da_thanh_toan', 'Dang_giao', 'Hoan_tat') DEFAULT 'Cho_duyet',
    CONSTRAINT fk_order_customer FOREIGN KEY (customer_id) REFERENCES Customers(customer_id),
    CONSTRAINT chk_positive_total CHECK (total_amount >= 0)
) ENGINE=InnoDB;

-- OrderDetails 
CREATE TABLE OrderDetails (
    order_id INT,
    product_id INT,
    quantity INT UNSIGNED NOT NULL,
    unit_price DECIMAL(10, 2) UNSIGNED NOT NULL,
    PRIMARY KEY (order_id, product_id),
    CONSTRAINT fk_detail_order FOREIGN KEY (order_id) REFERENCES Orders(order_id) ON DELETE CASCADE,
    CONSTRAINT fk_detail_product FOREIGN KEY (product_id) REFERENCES Products(product_id),
    CONSTRAINT chk_positive_quantity CHECK (quantity > 0) 
) ENGINE=InnoDB;

-- Payments 
CREATE TABLE Payments (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    payment_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    payment_method VARCHAR(50),
    amount DECIMAL(10, 2) UNSIGNED NOT NULL,
    note TEXT,
    CONSTRAINT fk_payment_order FOREIGN KEY (order_id) REFERENCES Orders(order_id),
    CONSTRAINT chk_positive_payment CHECK (amount >= 0)
) ENGINE=InnoDB;

-- Cart (Giỏ hàng tạm)
CREATE TABLE Cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    product_id INT,
    quantity INT,
    CONSTRAINT fk_cart_customer FOREIGN KEY (customer_id) REFERENCES Customers(customer_id) ON DELETE CASCADE,
    CONSTRAINT fk_cart_product FOREIGN KEY (product_id) REFERENCES Products(product_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 3. CHÈN DỮ LIỆU

-- Danh mục bánh
INSERT INTO Categories (category_name, description) VALUES
('Bánh Kem', 'Các dòng bánh gato, bánh sinh nhật trang trí kem tươi, yêu cầu bảo quản lạnh.'),
('Bánh Ngọt', 'Bánh có vị ngọt như Donut, Macaron, Muffins, thường dùng làm món tráng miệng.'),
('Bánh Mặn', 'Bánh có nhân thịt, chà bông hoặc phô mai như bánh mì xúc xích, bánh gối, bánh pateso.');

-- Sản phẩm
INSERT INTO Products (category_id, product_name, price, stock_quantity) VALUES
(1, 'Bánh Mousse Chanh Dây', 350000.00, 15),
(1, 'Bánh Red Velvet Cream', 320000.00, 10),
(1, 'Bánh Dark Chocolate', 270000.00, 5),
(1, 'Bánh Kem Phô Mai Việt Quất', 400000.00, 6),
(1, 'Bánh Tart Trái Cây Nhiệt Đới', 290000.00, 12),
(1, 'Bánh Kem Bắp Non', 380000.00, 8),
(1, 'Bánh Matcha Tiramisu Cake', 420000.00, 5),
(1, 'Bánh Kem Dâu Tây Đà Lạt', 450000.00, 4),

(2, 'Bánh Su Kem', 25000.00, 14),
(2, 'Bánh Tiramisu', 150000.00, 40),
(2, 'Bánh Macaron (Hộp 6 cái)', 120000.00, 25),
(2, 'Bánh Crepe Sầu Riêng', 45000.00, 30),
(2, 'Bánh Pancake Mật Ong', 55000.00, 15),
(2, 'Bánh Cupcake Vani', 20000.00, 40),
(2, 'Bánh Brownie Hạnh Nhân', 35000.00, 20),
(2, 'Bánh Donut Phủ Socola', 15000.00, 50),
(2, 'Bánh Muffin Việt Quất', 28000.00, 22),
(2, 'Bánh Cookies Bơ Sữa', 60000.00, 100),

(3, 'Bánh Bông Lan Trứng Muối', 75000.00, 20),
(3, 'Bánh Mì Chà Bông Cay', 30000.00, 20),
(3, 'Bánh Croissant Trứng Muối', 45000.00, 18),
(3, 'Bánh Mì Bơ Tỏi', 25000.00, 15),
(3, 'Bánh Mì Xúc Xích Phô Mai', 35000.00, 12),
(3, 'Bánh Hamburger Bò', 55000.00, 10),
(3, 'Bánh Gối Nhân Thịt Nấm', 15000.00, 45),
(3, 'Bánh Mì Que Hải Phòng', 10000.00, 60);

-- Khách hàng & Tài khoản
INSERT INTO Customers (full_name, address, phone_number, email) VALUES
('Nguyễn Minh Anh', '123 Quận 1, TP.HCM', '0901122334', 'vana@gmail.com'),
('Lê Văn An', 'Số 10 Tràng Thi, Hoàn Kiếm, Hà Nội', '0912334455', 'levanc@gmail.com'),
('Phạm Thanh Hải', 'Ngõ 123 Cầu Giấy, Dịch Vọng, Hà Nội', '0988776655', 'phamthid@gmail.com'),
('Hoàng Minh Thu', 'Số 45 Lê Văn Lương, Thanh Xuân, Hà Nội', '0944556677', 'hoangminhthu@gmail.com'),
('Ngô Thanh Vân', 'Chung cư Time City, Hai Bà Trưng, Hà Nội', '0903112233', 'ngothanhf@gmail.com'),
('Đặng Hoàng Anh', 'Số 5 Đường Mỹ Đình, Nam Từ Liêm, Hà Nội', '0333444555', 'danghoanganh@gmail.com'),
('Trần Phương Tuấn', '456 Quận 7, TP.HCM', '0905566778', 'jack@gmail.com');

INSERT INTO Accounts (username, password, customer_id, role) VALUES
('thanhhai_hn', '123456', 3, 'User'),
('minhthu_hn', '123456', 4, 'User'),
('thanhvan_hn', '123456', 5, 'User'),
('hoanganh_hn', '123456', 6, 'User'),
('tuanjack_hcm', '123456', 7, 'User');

-- 3.5. Hóa đơn 
INSERT INTO Orders (customer_id, subtotal, shipping_fee, total_amount, status) VALUES
(1, 350000.00, 30000.00, 380000.00, 'Hoan_tat'),     
(2, 700000.00, 20000.00, 720000.00, 'Hoan_tat'),       
(3, 150000.00, 20000.00, 170000.00, 'Dang_giao'),     
(4, 395000.00, 20000.00, 415000.00, 'Da_thanh_toan'),  
(5, 320000.00, 20000.00, 340000.00, 'Cho_duyet'),      
(6, 45000.00, 20000.00, 65000.00, 'Cho_duyet'),      
(7, 270000.00, 30000.00, 300000.00, 'Hoan_tat'),     
(2, 120000.00, 20000.00, 140000.00, 'Da_thanh_toan');  

-- 3.6. Chi tiết hóa đơn 
INSERT INTO OrderDetails (order_id, product_id, quantity, unit_price) VALUES
(1, 1, 1, 350000.00), 
(2, 1, 2, 350000.00), 
(3, 11, 1, 150000.00), 
(4, 4, 1, 75000.00), 
(4, 2, 1, 320000.00),  
(5, 2, 1, 320000.00), 
(6, 8, 1, 45000.00), 
(7, 3, 1, 270000.00), 
(8, 12, 1, 120000.00); 

-- 3.7. Payments 
INSERT INTO Payments (order_id, payment_method, amount, note) VALUES 
(1, 'Tiền mặt', 380000.00, 'Thanh toán tại cửa hàng'),
(2, 'Chuyển khoản VCB', 720000.00, 'Nguyễn Minh Anh chuyển khoản đơn 2'),
(4, 'Ví MoMo', 415000.00, 'Phạm Thanh Hải thanh toán MoMo'),
(7, 'Chuyển khoản BIDV', 300000.00, 'Trần Phương Tuấn thanh toán đơn 7'),
(8, 'Tiền mặt', 140000.00, 'Thanh toán khi nhận hàng đơn 8'),
(3, 'Ví ZaloPay', 170000.00, 'Tạm ứng trước đơn 3');

-- Giỏ hàng tạm
INSERT INTO Cart (customer_id, product_id, quantity) VALUES
(1, 11, 2), 
(3, 5, 1),
(4, 9, 4),
(2, 14, 2); 