-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 31, 2026 lúc 04:39 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `bakerystore`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `accounts`
--

CREATE TABLE `accounts` (
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `role` enum('Admin','User','staff') DEFAULT 'User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `accounts`
--

INSERT INTO `accounts` (`username`, `password`, `customer_id`, `role`) VALUES
('bong', '$2y$10$Ba0GmTBQDQqL1GkOaY0oV.916W7x8bdJPENHFpJcSTSHQ7ZfZXjp6', 10, 'Admin'),
('hoanganh_hn', '123456', 6, 'staff'),
('minhthu_hn', '123456', 4, 'User'),
('thanhhai_hn', '123456', 3, 'User'),
('thanhvan_hn', '123456', 5, 'User'),
('tuanjack_hcm', '123456', 7, 'User');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`cart_id`, `customer_id`, `product_id`, `quantity`) VALUES
(1, 1, 11, 2),
(2, 3, 5, 1),
(3, 4, 9, 4),
(4, 2, 14, 2),
(31, 8, 2, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `description`) VALUES
(1, 'Bánh Kem', 'Các dòng bánh gato, bánh sinh nhật trang trí kem tươi, yêu cầu bảo quản lạnh.'),
(2, 'Bánh Ngọt', 'Bánh có vị ngọt như Donut, Macaron, Muffins, thường dùng làm món tráng miệng.'),
(3, 'Bánh Mặn', 'Bánh có nhân thịt, chà bông hoặc phô mai như bánh mì xúc xích, bánh gối, bánh pateso.');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `address` text DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `customers`
--

INSERT INTO `customers` (`customer_id`, `full_name`, `address`, `phone_number`, `email`) VALUES
(1, 'Nguyễn Minh Anh', '123 Quận 1, TP.HCM', '0901122334', 'vana@gmail.com'),
(2, 'Lê Văn An', 'Số 10 Tràng Thi, Hoàn Kiếm, Hà Nội', '0912334455', 'levanc@gmail.com'),
(3, 'Phạm Thanh Hải', 'Ngõ 123 Cầu Giấy, Dịch Vọng, Hà Nội', '0988776655', 'phamthid@gmail.com'),
(4, 'Hoàng Minh Thu', 'Số 45 Lê Văn Lương, Thanh Xuân, Hà Nội', '0944556677', 'hoangminhthu@gmail.com'),
(5, 'Ngô Thanh Vân', 'Chung cư Time City, Hai Bà Trưng, Hà Nội', '0903112233', 'ngothanhf@gmail.com'),
(6, 'Đặng Hoàng Anh', 'Số 5 Đường Mỹ Đình, Nam Từ Liêm, Hà Nội', '0333444555', 'danghoanganh@gmail.com'),
(7, 'Trần Phương Tuấn', '456 Quận 7, TP.HCM', '0905566778', 'jack@gmail.com'),
(8, 'bông', '', '0123456789', 'anhduclcvn@gmail.com'),
(10, 'bong', '177pvz', '0969536683', 'anhduclcvn2@gmail.com');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orderdetails`
--

CREATE TABLE `orderdetails` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `unit_price` decimal(10,2) UNSIGNED NOT NULL
) ;

--
-- Đang đổ dữ liệu cho bảng `orderdetails`
--

INSERT INTO `orderdetails` (`order_id`, `product_id`, `quantity`, `unit_price`) VALUES
(1, 1, 1, 350000.00),
(2, 1, 2, 350000.00),
(3, 11, 1, 150000.00),
(4, 2, 1, 320000.00),
(4, 4, 1, 75000.00),
(5, 2, 1, 320000.00),
(6, 8, 1, 45000.00),
(7, 3, 1, 270000.00),
(8, 12, 1, 120000.00),
(9, 1, 1, 350000.00),
(10, 1, 10, 350000.00),
(11, 1, 1, 350000.00),
(12, 1, 10, 350000.00),
(13, 1, 4, 350000.00),
(14, 2, 1, 320000.00),
(15, 2, 1, 320000.00),
(16, 2, 5, 320000.00),
(17, 2, 1, 320000.00),
(18, 3, 8, 270000.00),
(19, 1, 1, 350000.00),
(20, 2, 4, 320000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `subtotal` decimal(10,2) UNSIGNED NOT NULL,
  `shipping_fee` decimal(10,2) UNSIGNED DEFAULT 0.00,
  `total_amount` decimal(10,2) UNSIGNED NOT NULL,
  `status` enum('Cho_duyet','Da_thanh_toan','Dang_giao','Hoan_tat') DEFAULT 'Cho_duyet'
) ;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `order_date`, `subtotal`, `shipping_fee`, `total_amount`, `status`) VALUES
(1, 1, '2026-05-05 22:22:56', 350000.00, 30000.00, 380000.00, 'Hoan_tat'),
(2, 2, '2026-05-05 22:22:56', 700000.00, 20000.00, 720000.00, 'Hoan_tat'),
(3, 3, '2026-05-05 22:22:56', 150000.00, 20000.00, 170000.00, 'Hoan_tat'),
(4, 4, '2026-05-05 22:22:56', 395000.00, 20000.00, 415000.00, 'Da_thanh_toan'),
(5, 5, '2026-05-05 22:22:56', 320000.00, 20000.00, 340000.00, 'Hoan_tat'),
(6, 6, '2026-05-05 22:22:56', 45000.00, 20000.00, 65000.00, 'Dang_giao'),
(7, 7, '2026-05-05 22:22:56', 270000.00, 30000.00, 300000.00, 'Hoan_tat'),
(8, 2, '2026-05-05 22:22:56', 120000.00, 20000.00, 140000.00, 'Da_thanh_toan'),
(9, 8, '2026-05-05 22:24:09', 350000.00, 30000.00, 408000.00, 'Cho_duyet'),
(10, 8, '2026-05-05 22:27:42', 3500000.00, 30000.00, 3810000.00, 'Cho_duyet'),
(11, 8, '2026-05-05 22:29:51', 350000.00, 30000.00, 408000.00, 'Da_thanh_toan'),
(12, 8, '2026-05-05 22:31:39', 3500000.00, 30000.00, 3810000.00, 'Cho_duyet'),
(13, 8, '2026-05-06 19:08:43', 1400000.00, 30000.00, 1542000.00, 'Cho_duyet'),
(14, 8, '2026-05-06 19:15:23', 320000.00, 30000.00, 375600.00, 'Cho_duyet'),
(15, 8, '2026-05-06 19:23:05', 320000.00, 30000.00, 375600.00, 'Dang_giao'),
(16, 8, '2026-05-07 20:45:03', 1600000.00, 30000.00, 1758000.00, 'Hoan_tat'),
(17, 8, '2026-05-07 20:45:45', 320000.00, 30000.00, 375600.00, 'Hoan_tat'),
(18, 8, '2026-05-08 09:27:10', 2160000.00, 30000.00, 2362800.00, 'Hoan_tat'),
(19, 10, '2026-05-30 20:11:44', 350000.00, 30000.00, 408000.00, 'Hoan_tat'),
(20, 10, '2026-05-31 20:19:43', 1280000.00, 30000.00, 1412400.00, 'Cho_duyet'),
(21, 10, '2026-05-31 20:45:33', 99999999.99, 30000.00, 99999999.99, 'Dang_giao');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payments`
--

CREATE TABLE `payments` (
  `transaction_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `payment_date` datetime DEFAULT current_timestamp(),
  `payment_method` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) UNSIGNED NOT NULL,
  `note` text DEFAULT NULL
) ;

--
-- Đang đổ dữ liệu cho bảng `payments`
--

INSERT INTO `payments` (`transaction_id`, `order_id`, `payment_date`, `payment_method`, `amount`, `note`) VALUES
(1, 1, '2026-05-05 22:22:56', 'Tiền mặt', 380000.00, 'Thanh toán tại cửa hàng'),
(2, 2, '2026-05-05 22:22:56', 'Chuyển khoản VCB', 720000.00, 'Nguyễn Minh Anh chuyển khoản đơn 2'),
(3, 4, '2026-05-05 22:22:56', 'Ví MoMo', 415000.00, 'Phạm Thanh Hải thanh toán MoMo'),
(4, 7, '2026-05-05 22:22:56', 'Chuyển khoản BIDV', 300000.00, 'Trần Phương Tuấn thanh toán đơn 7'),
(5, 8, '2026-05-05 22:22:56', 'Tiền mặt', 140000.00, 'Thanh toán khi nhận hàng đơn 8'),
(6, 3, '2026-05-05 22:22:56', 'Ví ZaloPay', 170000.00, 'Tạm ứng trước đơn 3'),
(7, 11, '2026-05-05 22:29:51', 'Bank Transfer', 408000.00, 'Bank transfer - bông - 0123456789. ');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) UNSIGNED NOT NULL,
  `stock_quantity` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `image` varchar(255) NOT NULL
) ;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `product_name`, `price`, `stock_quantity`, `image`) VALUES
(1, 1, 'Bánh Mousse Chanh Dây', 350000.00, 99, 'mouse-chanh-day.jpg'),
(2, 1, 'Bánh Red Velvet Cream', 320000.00, 2096, 'redvelet-cream.png'),
(3, 1, 'Bánh Dark Chocolate', 270000.00, 42, 'dark-chocolate.jpg'),
(4, 1, 'Bánh Kem Phô Mai Việt Quất', 400000.00, 60, 'kem-pho-mai-viet-quat.jpg'),
(5, 1, 'Bánh Tart Trái Cây Nhiệt Đới', 290000.00, 12, 'tart-trai-cay-nhiet-doi.jpg'),
(6, 1, 'Bánh Kem Bắp Non', 380000.00, 8, 'kem-bap-non.jpg'),
(7, 1, 'Bánh Matcha Tiramisu Cake', 420000.00, 50, 'matcha-tiramisu.png'),
(8, 1, 'Bánh Kem Dâu Tây Đà Lạt', 450000.00, 40, 'kem-dau-tay-dalat.jpg'),
(9, 2, 'Bánh Su Kem', 25000.00, 14, 'banhsukem.jpg'),
(10, 2, 'Bánh Tiramisu', 150000.00, 40, 'tiramisu.jpg'),
(11, 2, 'Bánh Macaron (Hộp 6 cái)', 120000.00, 25, 'macarron.jpg'),
(12, 2, 'Bánh Crepe Sầu Riêng', 45000.00, 30, 'crepe-sau-rieng.jpg'),
(13, 2, 'Bánh Pancake Mật Ong', 55000.00, 15, 'Bánh Pancake Mật Ong.jpg'),
(14, 2, 'Bánh Cupcake Vani', 20000.00, 40, 'Bánh Cupcake Vani.jpg'),
(15, 2, 'Bánh Brownie Hạnh Nhân', 35000.00, 20, 'Bánh Brownie Hạnh Nhân.jpg'),
(16, 2, 'Bánh Donut Phủ Socola', 15000.00, 50, 'Bánh Donut Phủ Socola.jpg'),
(17, 2, 'Bánh Muffin Việt Quất', 28000.00, 22, 'banh-muffin-viet-quat.jpg'),
(18, 2, 'Bánh Cookies Bơ Sữa', 60000.00, 100, 'cookies-bo-sua.jpg'),
(19, 3, 'Bánh Bông Lan Trứng Muối', 75000.00, 20, 'banh-bong-lan-trung-muoi.jpg'),
(20, 3, 'Bánh Mì Chà Bông Cay', 30000.00, 20, 'banh-mi-cha-bong-cay.jpg'),
(21, 3, 'Bánh Croissant Trứng Muối', 45000.00, 18, 'croissant-trung-muoi.jpg'),
(22, 3, 'Bánh Mì Bơ Tỏi', 25000.00, 15, 'banh-mi-bo-toi.jpg'),
(23, 3, 'Bánh Mì Xúc Xích Phô Mai', 35000.00, 12, 'banh-mi-xuc-xich-phon-mai.jpg'),
(24, 3, 'Bánh Hamburger Bò', 55000.00, 10, 'hamburger-bo.jpg'),
(25, 3, 'Bánh Gối Nhân Thịt Nấm', 15000.00, 45, 'banh-goi-nhan-thit-nam.jpg');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`username`),
  ADD KEY `fk_account_customer` (`customer_id`);

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `fk_cart_customer` (`customer_id`),
  ADD KEY `fk_cart_product` (`product_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Chỉ mục cho bảng `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`order_id`,`product_id`),
  ADD KEY `fk_detail_product` (`product_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_order_customer` (`customer_id`);

--
-- Chỉ mục cho bảng `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `fk_payment_order` (`order_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `fk_product_category` (`category_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `payments`
--
ALTER TABLE `payments`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `fk_account_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `fk_detail_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_detail_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_order_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);

--
-- Các ràng buộc cho bảng `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payment_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
