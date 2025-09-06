<?php
require_once 'includes/auth.php';

// Handle logout
if (isset($_GET['logout'])) {
    logoutUser();
}

$user = getCurrentUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoFinds - Sustainable Second-Hand Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .feature-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-leaf text-success"></i> EcoFinds
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">Browse Products</a>
                    </li>
                    <?php if ($user): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">
                            <i class="fas fa-shopping-cart"></i> Cart
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if ($user): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($user['username']); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li><a class="dropdown-item" href="my-products.php">My Products</a></li>
                            <li><a class="dropdown-item" href="purchase-history.php">Purchase History</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="?logout=1">Logout</a></li>
                        </ul>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 mb-4">Welcome to EcoFinds</h1>
            <p class="lead mb-4">Empowering Sustainable Consumption through a Second-Hand Marketplace</p>
            <p class="mb-5">Join our community to buy and sell pre-owned goods, reduce waste, and make sustainable choices easier for everyone.</p>
            <?php if (!$user): ?>
            <a href="register.php" class="btn btn-primary btn-lg me-3">Get Started</a>
            <a href="products.php" class="btn btn-outline-light btn-lg">Browse Products</a>
            <?php else: ?>
            <a href="products.php" class="btn btn-primary btn-lg me-3">Browse Products</a>
            <a href="dashboard.php" class="btn btn-outline-light btn-lg">My Dashboard</a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Why Choose EcoFinds?</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100 text-center p-4">
                        <div class="card-body">
                            <i class="fas fa-recycle fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Sustainable Living</h5>
                            <p class="card-text">Extend the lifecycle of products and reduce waste by giving items a second life.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100 text-center p-4">
                        <div class="card-body">
                            <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Trusted Platform</h5>
                            <p class="card-text">Buy and sell with confidence in our secure and user-friendly marketplace.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100 text-center p-4">
                        <div class="card-body">
                            <i class="fas fa-users fa-3x text-info mb-3"></i>
                            <h5 class="card-title">Community Driven</h5>
                            <p class="card-text">Join a conscious community seeking unique finds and responsible consumption.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Preview -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Shop by Category</h2>
            <div class="row">
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-laptop fa-2x text-primary mb-2"></i>
                            <h6>Electronics</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-tshirt fa-2x text-success mb-2"></i>
                            <h6>Clothing</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-book fa-2x text-warning mb-2"></i>
                            <h6>Books</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-home fa-2x text-info mb-2"></i>
                            <h6>Home & Garden</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="products.php" class="btn btn-primary">View All Categories</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p>&copy; 2024 EcoFinds. Empowering sustainable consumption through second-hand marketplace.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
