-- 1. TẠO DATABASE
CREATE DATABASE IF NOT EXISTS CuaHangBanh;
USE CuaHangBanh;
-- 2. TẠO CÁC BẢNG 

-- Bảng Loại hàng
CREATE TABLE Loai_hang (
    Ma_loai INT AUTO_INCREMENT PRIMARY KEY,
    Ten_loai VARCHAR(100) NOT NULL,
    Mo_ta TEXT
) ENGINE=InnoDB;

-- Bảng Sản phẩm 
-- 2. Bảng Sản phẩm (Thêm UNSIGNED và CHECK cho Gia/So_luong)
CREATE TABLE San_Pham (
    Ma_SP INT AUTO_INCREMENT PRIMARY KEY,
    Ma_loai INT,
    Ten_SP VARCHAR(255) NOT NULL,
    Gia DECIMAL(10, 2) UNSIGNED NOT NULL, 
    So_luong INT UNSIGNED NOT NULL DEFAULT 0, 
    CONSTRAINT fk_sanpham_loai FOREIGN KEY (Ma_loai) REFERENCES Loai_hang(Ma_loai) ON DELETE SET NULL,
    CONSTRAINT chk_gia_duong CHECK (Gia >= 0),
    CONSTRAINT chk_soluong_duong CHECK (So_luong >= 0)
) ENGINE=InnoDB;

-- Bảng Khách hàng
CREATE TABLE Khach_hang (
    Ma_KH INT AUTO_INCREMENT PRIMARY KEY,
    Ten_KH VARCHAR(100) NOT NULL,
    Dia_chi TEXT,
    Dien_thoai VARCHAR(15),
    Email VARCHAR(100) UNIQUE
) ENGINE=InnoDB;

-- Bảng Tài khoản (Đăng nhập)
CREATE TABLE Tai_khoan (
    Ten_dang_nhap VARCHAR(50) PRIMARY KEY,
    Mat_khau VARCHAR(255) NOT NULL,
    Ma_KH INT,
    Quyen ENUM('Admin', 'User') DEFAULT 'User',
    CONSTRAINT fk_taikhoan_khachhang FOREIGN KEY (Ma_KH) REFERENCES Khach_hang(Ma_KH) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 5. Bảng Hóa đơn 
CREATE TABLE Hoa_don (
    Ma_HD INT AUTO_INCREMENT PRIMARY KEY,
    Ma_KH INT,
    Ngay_dat DATETIME DEFAULT CURRENT_TIMESTAMP,
    Tong_tien_hang DECIMAL(10, 2) UNSIGNED NOT NULL,
    Phi_ship DECIMAL(10, 2) UNSIGNED DEFAULT 0,
    Tong_thanh_toan DECIMAL(10, 2) UNSIGNED NOT NULL,
    Trang_thai ENUM('Cho_duyet', 'Da_thanh_toan', 'Dang_giao', 'Hoan_tat') DEFAULT 'Cho_duyet',
    CONSTRAINT fk_hoadon_khachhang FOREIGN KEY (Ma_KH) REFERENCES Khach_hang(Ma_KH),
    CONSTRAINT chk_tongtien CHECK (Tong_thanh_toan >= 0)
) ENGINE=InnoDB;

-- 6. Bảng Chi tiết hóa đơn
CREATE TABLE Chi_tiet_hoa_don (
    Ma_HD INT,
    Ma_SP INT,
    So_luong INT UNSIGNED NOT NULL,
    Gia_ban DECIMAL(10, 2) UNSIGNED NOT NULL,
    PRIMARY KEY (Ma_HD, Ma_SP),
    CONSTRAINT fk_cthd_hoadon FOREIGN KEY (Ma_HD) REFERENCES Hoa_don(Ma_HD) ON DELETE CASCADE,
    CONSTRAINT fk_cthd_sanpham FOREIGN KEY (Ma_SP) REFERENCES San_Pham(Ma_SP),
    CONSTRAINT chk_soluong_mua CHECK (So_luong > 0) 
) ENGINE=InnoDB;

-- 7. Bảng Thanh toán 
CREATE TABLE Thanh_toan (
    Ma_GD INT AUTO_INCREMENT PRIMARY KEY,
    Ma_HD INT,
    Ngay_GD DATETIME DEFAULT CURRENT_TIMESTAMP,
    Phuong_thuc VARCHAR(50),
    So_tien DECIMAL(10, 2) UNSIGNED NOT NULL,
    Noi_dung TEXT,
    CONSTRAINT fk_thanhtoan_hoadon FOREIGN KEY (Ma_HD) REFERENCES Hoa_don(Ma_HD),
    CONSTRAINT chk_sotien_tt CHECK (So_tien >= 0)
) ENGINE=InnoDB;

-- Bảng Giỏ hàng tạm (Lưu trữ trạng thái cho người dùng đăng nhập)
CREATE TABLE Gio_hang_tam (
    Ma_GH INT AUTO_INCREMENT PRIMARY KEY,
    Ma_KH INT,
    Ma_SP INT,
    So_luong INT,
    CONSTRAINT fk_giohang_khachhang FOREIGN KEY (Ma_KH) REFERENCES Khach_hang(Ma_KH) ON DELETE CASCADE,
    CONSTRAINT fk_giohang_sanpham FOREIGN KEY (Ma_SP) REFERENCES San_Pham(Ma_SP) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- 3. CHÈN DỮ LIỆU MẪU (INSERT VALUES)

-- Danh mục bánh
INSERT INTO Loai_hang (Ten_loai, Mo_ta) VALUES 
('Bánh Kem', 'Các dòng bánh gato, bánh sinh nhật trang trí kem tươi, yêu cầu bảo quản lạnh.'),
('Bánh Ngọt', 'Bánh có vị ngọt như Donut, Macaron, Muffins, thường dùng làm món tráng miệng.'),
('Bánh Mặn', 'Bánh có nhân thịt, chà bông hoặc phô mai như bánh mì xúc xích, bánh gối, bánh pateso.');

-- Sản phẩm
INSERT INTO San_Pham (Ma_loai, Ten_SP, Gia, So_luong) VALUES 
(1, 'Bánh Mousse Chanh Dây', 350000.00, 15),
(1, 'Bánh Red Velvet Cream', 320000.00, 10),
(1, 'Bánh Dark Chocolate', 270000.00, 5),
(3, 'Bánh Bông Lan Trứng Muối', 75000.00, 20),
(1, 'Bánh Kem Phô Mai Việt Quất', 400000.00, 6),
(1, 'Bánh Tart Trái Cây Nhiệt Đới', 290000.00, 12),
(3, 'Bánh Mì Chà Bông Cay', 30000.00, 20),
(3, 'Bánh Croissant Trứng Muối', 45000.00, 18),
(3, 'Bánh Mì Bơ Tỏi', 25000.00, 15),
(2, 'Bánh Su Kem', 25000.00, 14),
(2, 'Bánh Tiramisu', 150000.00, 40),
(2, 'Bánh Macaron (Hộp 6 cái)', 120000.00, 25),
(2, 'Bánh Crepe Sầu Riêng', 45000.00, 30),
(2, 'Bánh Pancake Mật Ong', 55000.00, 15),
(2, 'Bánh Cupcake Vani', 20000.00, 40);

-- Khách hàng & Tài khoản
INSERT INTO Khach_hang (Ten_KH, Dia_chi, Dien_thoai, Email) VALUES 
('Nguyễn Minh Anh', '123 Quận 1, TP.HCM', '0901122334', 'vana@gmail.com'),
('Lê Văn An', 'Số 10 Tràng Thi, Hoàn Kiếm, Hà Nội', '0912334455', 'levanc@gmail.com'),
('Phạm Thanh Hải', 'Ngõ 123 Cầu Giấy, Dịch Vọng, Hà Nội', '0988776655', 'phamthid@gmail.com'),
('Hoàng Minh Thu', 'Số 45 Lê Văn Lương, Thanh Xuân, Hà Nội', '0944556677', 'hoangminhthu@gmail.com'),
('Ngô Thanh Vân', 'Chung cư Time City, Hai Bà Trưng, Hà Nội', '0903112233', 'ngothanhf@gmail.com'),
('Đặng Hoàng Anh', 'Số 5 Đường Mỹ Đình, Nam Từ Liêm, Hà Nội', '0333444555', 'danghoanganh@gmail.com'),
('Trần Phương Tuấn', '456 Quận 7, TP.HCM', '0905566778', 'jack@gmail.com');

INSERT INTO Tai_khoan (Ten_dang_nhap, Mat_khau, Ma_KH, Quyen) VALUES 
('thanhhai_hn', '123456', 3, 'User'),
('minhthu_hn', '123456', 4, 'User'),
('thanhvan_hn', '123456', 5, 'User'),
('hoanganh_hn', '123456', 6, 'User'),
('tuanjack_hcm', '123456', 7, 'User');

-- 3.5. Hóa đơn 
INSERT INTO Hoa_don (Ma_KH, Tong_tien_hang, Phi_ship, Tong_thanh_toan, Trang_thai) VALUES 
(1, 350000.00, 30000.00, 380000.00, 'Hoan_tat'),     
(2, 700000.00, 20000.00, 720000.00, 'Hoan_tat'),       
(3, 150000.00, 20000.00, 170000.00, 'Dang_giao'),     
(4, 395000.00, 20000.00, 415000.00, 'Da_thanh_toan'),  
(5, 320000.00, 20000.00, 340000.00, 'Cho_duyet'),      
(6, 45000.00, 20000.00, 65000.00, 'Cho_duyet'),      
(7, 270000.00, 30000.00, 300000.00, 'Hoan_tat'),     
(2, 120000.00, 20000.00, 140000.00, 'Da_thanh_toan');  

-- 3.6. Chi tiết hóa đơn 
INSERT INTO Chi_tiet_hoa_don (Ma_HD, Ma_SP, So_luong, Gia_ban) VALUES 
(1, 1, 1, 350000.00), 
(2, 1, 2, 350000.00), 
(3, 11, 1, 150000.00), 
(4, 4, 1, 75000.00), 
(4, 2, 1, 320000.00),  
(5, 2, 1, 320000.00), 
(6, 8, 1, 45000.00), 
(7, 3, 1, 270000.00), 
(8, 12, 1, 120000.00); 

INSERT INTO Thanh_toan (Ma_HD, Phuong_thuc, So_tien, Noi_dung) VALUES 
(1, 'Tiền mặt', 380000.00, 'Thanh toán tại cửa hàng'),
(2, 'Chuyển khoản VCB', 720000.00, 'Nguyễn Minh Anh chuyển khoản đơn 2'),
(4, 'Ví MoMo', 415000.00, 'Phạm Thanh Hải thanh toán MoMo'),
(7, 'Chuyển khoản BIDV', 300000.00, 'Trần Phương Tuấn thanh toán đơn 7'),
(8, 'Tiền mặt', 140000.00, 'Thanh toán khi nhận hàng đơn 8'),
(3, 'Ví ZaloPay', 170000.00, 'Tạm ứng trước đơn 3');

-- Giỏ hàng tạm
INSERT INTO Gio_hang_tam (Ma_KH, Ma_SP, So_luong) VALUES 
(1, 11, 2), 
(3, 5, 1),
(4, 9, 4),
(2, 14, 2); 