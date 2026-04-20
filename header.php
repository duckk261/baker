<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once 'db_connect.php'; 
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Baker</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <link href="img/favicon.ico" rel="icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet"> 

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>

    <div class="container-fluid top-bar bg-dark text-light px-0 wow fadeIn" data-wow-delay="0.1s">
        <div class="row gx-0 align-items-center d-none d-lg-flex">
            <div class="col-lg-6 px-5 text-start">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a class="small text-light" href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a class="small text-light" href="#">Privacy Policy</a></li>
                    <li class="breadcrumb-item"><a class="small text-light" href="#">Terms</a></li>
                </ol>
            </div>
            <div class="col-lg-6 px-5 text-end">
                <small>Follow us on:</small>
                <div class="h-100 d-inline-flex align-items-center">
                    <a class="btn-lg-square text-primary border-end rounded-0" href=""><i class="fab fa-facebook-f"></i></a>
                    <a class="btn-lg-square text-primary border-end rounded-0" href=""><i class="fab fa-twitter"></i></a>
                    <a class="btn-lg-square text-primary pe-0" href=""><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top py-lg-0 px-lg-5 wow fadeIn" data-wow-delay="0.1s">
        <a href="index.php" class="navbar-brand ms-4 ms-lg-0">
            <h1 class="text-primary m-0">Baker</h1>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
            <div class="navbar-nav mx-auto p-4 p-lg-0">
                <a href="index.php" class="nav-item nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Home</a>
                <a href="about.php" class="nav-item nav-link <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>">About Us</a>
                <a href="product.php" class="nav-item nav-link <?php echo ($current_page == 'product.php') ? 'active' : ''; ?>">Products</a>
                <a href="contact.php" class="nav-item nav-link <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>">Contact</a>
            </div>
           <div class="navbar-nav ms-auto p-4 p-lg-0">
    <a href="index.php" class="nav-item nav-link">Home</a>
    <a href="product.php" class="nav-item nav-link">Products</a>
    
   <div class="ms-4 d-flex align-items-center gap-3">
    <?php if (isset($_SESSION['account_name'])): ?>
        <div class="nav-item dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                <div class="flex-shrink-0 btn-sm-square border border-light rounded-circle me-2">
                    <i class="fa fa-user text-primary"></i>
                </div>
                <span class="text-light small d-none d-lg-inline-block">
                    Hi, <?php echo $_SESSION['account_name']; ?>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-end m-0">
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'Admin'): ?>
        <a href="admin/index.php" class="dropdown-item fw-bold text-primary">
            <i class="fa fa-user-shield me-2"></i>Store Management
        </a>
        <div class="dropdown-divider"></div>
    <?php endif; ?>

    <a href="profile.php" class="dropdown-item">My Profile</a>
    <div class="dropdown-divider"></div>
    <a href="logout.php" class="dropdown-item text-danger">Logout</a>
</div>
        </div>
    <?php else: ?>
        <a href="login.php" title="Login" class="d-flex align-items-center text-decoration-none">
            <div class="flex-shrink-0 btn-sm-square border border-light rounded-circle">
                <i class="fa fa-user text-primary"></i>
            </div>
        </a>
    <?php endif; ?>

    <a href="cart.php" title="Shopping Cart" class="d-flex align-items-center text-decoration-none position-relative">
        <div class="flex-shrink-0 btn-sm-square border border-light rounded-circle">
            <i class="fa fa-shopping-cart text-primary"></i>
        </div>
        <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
            <?php echo $cart_count; ?>
        </span>
    </a>
</div>
            </div>
        </div>
    </nav>

    <?php if ($current_page != 'index.php'): ?>
    <div class="container-fluid page-header py-6 wow fadeIn" data-wow-delay="0.1s">
        <div class="container text-center pt-5 pb-3">
            <h1 class="display-4 text-white animated slideInDown mb-3">
                <?php 
                    if ($current_page == 'about.php') echo 'About Us';
                    elseif ($current_page == 'product.php') echo 'Our Products';
                    elseif ($current_page == 'contact.php') echo 'Contact Us';
                    elseif ($current_page == 'team.php') echo 'Our Team';
                    else echo 'Explore';
                ?>
            </h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a class="text-white" href="index.php">Home</a></li>
                    <li class="breadcrumb-item text-primary active" aria-current="page">
                        <?php echo str_replace('.php', '', ucfirst($current_page)); ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <?php endif; ?>