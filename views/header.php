<?php 
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
$current_page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Baker - MVC System</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link href="assets/img/favicon.ico" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="assets/lib/animate/animate.min.css" rel="stylesheet">
    <link href="assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top py-lg-0 px-lg-5 wow fadeIn" data-wow-delay="0.1s">
        <a href="index.php?page=home" class="navbar-brand ms-4 ms-lg-0">
            <h1 class="text-primary m-0">Baker</h1>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav mx-auto p-4 p-lg-0">
                <a href="index.php?page=home" class="nav-item nav-link <?php echo ($current_page == 'home') ? 'active' : ''; ?>">Home</a>
                <a href="index.php?page=about" class="nav-item nav-link <?php echo ($current_page == 'about') ? 'active' : ''; ?>">About Us</a>
                <a href="index.php?page=product" class="nav-item nav-link <?php echo ($current_page == 'product') ? 'active' : ''; ?>">Products</a>
                <a href="index.php?page=contact" class="nav-item nav-link <?php echo ($current_page == 'contact') ? 'active' : ''; ?>">Contact</a>
            </div>
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <div class="ms-4 d-flex align-items-center gap-3">
    
    <?php if (isset($_SESSION['account_name'])): ?>
        <div class="nav-item dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                <div class="flex-shrink-0 btn-sm-square border border-light rounded-circle">
                    <i class="fa fa-user text-primary"></i>
                </div>
                <span class="text-light small d-none d-lg-inline-block ms-2">
                    Hi, <?php echo $_SESSION['account_name']; ?>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-end m-0">
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'Admin'): ?>
                    <a href="index.php?page=admin" class="dropdown-item fw-bold text-primary">
                        <i class="fa fa-user-shield me-2"></i>Store Management
                    </a>
                    <div class="dropdown-divider"></div>
                <?php endif; ?>
                <a href="index.php?page=profile" class="dropdown-item">My Profile</a>
                <div class="dropdown-divider"></div>
                <a href="index.php?page=logout" class="dropdown-item text-danger">Logout</a>
            </div>
        </div>
    <?php else: ?>
        <a href="index.php?page=login" title="Login" class="d-flex align-items-center text-decoration-none">
            <div class="flex-shrink-0 btn-sm-square border border-light rounded-circle">
                <i class="fa fa-user text-primary"></i>
            </div>
        </a>
    <?php endif; ?>

    <?php if (isset($_SESSION['account_id'])): ?>
        <a href="index.php?page=cart" title="Shopping Cart" class="d-flex align-items-center text-decoration-none position-relative">
            <div class="flex-shrink-0 btn-sm-square border border-light rounded-circle">
                <i class="fa fa-shopping-cart text-primary"></i>
            </div>
            <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                <?php echo $cart_count; ?>
            </span>
        </a>
    <?php else: ?>
        <a href="javascript:void(0);" onclick="alert('Bạn cần đăng nhập để xem giỏ hàng!'); window.location.href='index.php?page=login';" title="Shopping Cart" class="d-flex align-items-center text-decoration-none position-relative">
            <div class="flex-shrink-0 btn-sm-square border border-light rounded-circle">
                <i class="fa fa-shopping-cart text-primary"></i>
            </div>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary" style="font-size: 10px;">
                0
            </span>
        </a>
    <?php endif; ?>
</div>
            </div>
        </div>
    </nav>

    <?php if ($current_page != 'home'): ?>
    <div class="container-fluid page-header py-6 wow fadeIn" data-wow-delay="0.1s">
        <div class="container text-center pt-5 pb-3">
            <h1 class="display-4 text-white animated slideInDown mb-3">
                <?php echo ucfirst($current_page); ?>
            </h1>
        </div>
    </div>
    <?php endif; ?>