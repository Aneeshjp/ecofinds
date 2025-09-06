<?php
require_once 'includes/auth.php';
require_once 'includes/cart.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$user = getCurrentUser();
$purchases = getUserPurchases($user['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase History - EcoFinds</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
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
        .purchase-card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .purchase-card:hover {
            transform: translateY(-2px);
        }
        .status-badge {
            font-size: 0.8rem;
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
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">
                            <i class="fas fa-shopping-cart"></i> Cart
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
                            <li><a class="dropdown-item active" href="purchase-history.php">Purchase History</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="?logout=1">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-history"></i> Purchase History</h2>
                    <a href="products.php" class="btn btn-primary">
                        <i class="fas fa-shopping-bag"></i> Continue Shopping
                    </a>
                </div>

                <?php if (empty($purchases)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No purchases yet</h4>
                    <p class="text-muted">Start shopping to see your purchase history here!</p>
                    <a href="products.php" class="btn btn-primary">
                        <i class="fas fa-shopping-bag"></i> Browse Products
                    </a>
                </div>
                <?php else: ?>
                <div class="row">
                    <?php foreach ($purchases as $purchase): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card purchase-card h-100">
                            <img src="uploads/<?php echo htmlspecialchars($purchase['image_path']); ?>" 
                                 class="card-img-top" alt="<?php echo htmlspecialchars($purchase['title']); ?>" 
                                 style="height: 200px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title"><?php echo htmlspecialchars($purchase['title']); ?></h6>
                                <p class="card-text text-muted small">Sold by: <?php echo htmlspecialchars($purchase['seller_name']); ?></p>
                                <p class="card-text"><?php echo substr(htmlspecialchars($purchase['description']), 0, 100); ?>...</p>
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt"></i> 
                                        Delivered to: <?php echo htmlspecialchars($purchase['full_name']); ?><br>
                                        <?php echo htmlspecialchars($purchase['address_line1']); ?>, 
                                        <?php echo htmlspecialchars($purchase['city']); ?>, 
                                        <?php echo htmlspecialchars($purchase['state']); ?> <?php echo htmlspecialchars($purchase['postal_code']); ?><br>
                                        <i class="<?php echo htmlspecialchars($purchase['payment_method_icon'] ?? 'fas fa-credit-card'); ?>"></i>
                                        Paid with: <?php echo htmlspecialchars($purchase['payment_method_name']); ?>
                                    </small>
                                </div>
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-success"><strong>$<?php echo number_format($purchase['purchase_price'], 2); ?></strong></span>
                                        <span class="badge bg-success status-badge">Purchased</span>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar"></i> 
                                        <?php echo date('M j, Y', strtotime($purchase['purchase_date'])); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Purchase Summary -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5><i class="fas fa-chart-pie"></i> Purchase Summary</h5>
                                <p class="mb-1"><strong><?php echo count($purchases); ?></strong> total purchases</p>
                                <p class="mb-1"><strong>$<?php echo number_format(array_sum(array_column($purchases, 'purchase_price')), 2); ?></strong> total spent</p>
                                <p class="mb-0"><strong><?php echo count(array_unique(array_column($purchases, 'seller_id'))); ?></strong> different sellers</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5><i class="fas fa-leaf"></i> Environmental Impact</h5>
                                <p class="mb-1"><strong><?php echo count($purchases); ?></strong> items given a second life</p>
                                <p class="mb-1"><strong><?php echo count($purchases); ?></strong> new items not manufactured</p>
                                <p class="mb-0 text-success"><i class="fas fa-heart"></i> Thank you for choosing sustainable shopping!</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
