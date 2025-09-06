<?php
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$user = getCurrentUser();
$pdo = getDBConnection();

// Get user's product count
$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM products WHERE seller_id = ?");
$stmt->execute([$user['id']]);
$productCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Get cart count
$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM cart WHERE user_id = ?");
$stmt->execute([$user['id']]);
$cartCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Get purchase count
$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM purchases WHERE buyer_id = ?");
$stmt->execute([$user['id']]);
$purchaseCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Get recent products
$stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p 
                      JOIN categories c ON p.category_id = c.id 
                      WHERE p.seller_id = ? 
                      ORDER BY p.created_at DESC LIMIT 5");
$stmt->execute([$user['id']]);
$recentProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - EcoFinds</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .dashboard-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
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
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">
                            <i class="fas fa-shopping-cart"></i> Cart
                            <?php if ($cartCount > 0): ?>
                            <span class="badge bg-danger"><?php echo $cartCount; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
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
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <h2>Welcome back, <?php echo htmlspecialchars($user['username']); ?>!</h2>
                <p class="text-muted">Manage your EcoFinds account and activities</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-5">
            <div class="col-md-4 mb-3">
                <div class="card stat-card dashboard-card">
                    <div class="card-body text-center">
                        <i class="fas fa-box fa-3x mb-3"></i>
                        <h3><?php echo $productCount; ?></h3>
                        <p class="mb-0">Products Listed</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card stat-card dashboard-card">
                    <div class="card-body text-center">
                        <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                        <h3><?php echo $cartCount; ?></h3>
                        <p class="mb-0">Items in Cart</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card stat-card dashboard-card">
                    <div class="card-body text-center">
                        <i class="fas fa-shopping-bag fa-3x mb-3"></i>
                        <h3><?php echo $purchaseCount; ?></h3>
                        <p class="mb-0">Purchases Made</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-5">
            <div class="col-12">
                <h4>Quick Actions</h4>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <i class="fas fa-plus-circle fa-2x text-primary mb-3"></i>
                        <h6>Add New Product</h6>
                        <a href="add-product.php" class="btn btn-primary btn-sm">Create Listing</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <i class="fas fa-box fa-2x text-success mb-3"></i>
                        <h6>My Products</h6>
                        <a href="my-products.php" class="btn btn-success btn-sm">View All</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <i class="fas fa-search fa-2x text-info mb-3"></i>
                        <h6>Browse Products</h6>
                        <a href="products.php" class="btn btn-info btn-sm">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <i class="fas fa-user-edit fa-2x text-warning mb-3"></i>
                        <h6>Edit Profile</h6>
                        <a href="profile.php" class="btn btn-warning btn-sm">Update</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Products -->
        <?php if (!empty($recentProducts)): ?>
        <div class="row">
            <div class="col-12">
                <h4>Your Recent Products</h4>
                <div class="row">
                    <?php foreach ($recentProducts as $product): ?>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card dashboard-card">
                            <img src="uploads/<?php echo htmlspecialchars($product['image_path']); ?>" 
                                 class="card-img-top" alt="<?php echo htmlspecialchars($product['title']); ?>" 
                                 style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h6 class="card-title"><?php echo htmlspecialchars($product['title']); ?></h6>
                                <p class="card-text text-muted small"><?php echo htmlspecialchars($product['category_name']); ?></p>
                                <p class="card-text"><strong>$<?php echo number_format($product['price'], 2); ?></strong></p>
                                <a href="product-detail.php?id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="text-center">
                    <a href="my-products.php" class="btn btn-outline-primary">View All Products</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
