<?php
require_once 'includes/auth.php';
require_once 'includes/products.php';
require_once 'includes/addresses.php';
require_once 'includes/payments.php';

$user = getCurrentUser();
$userAddresses = $user ? getUserAddresses($user['id']) : [];
$paymentMethods = getAllPaymentMethods();

// Get product ID
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$productId) {
    header('Location: products.php');
    exit();
}

// Get product details
$product = getProductById($productId);
if (!$product) {
    header('Location: products.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['title']); ?> - EcoFinds</title>
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
        .product-detail-card {
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .product-image {
            max-height: 500px;
            object-fit: cover;
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

    <div class="container mt-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="products.php">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product['title']); ?></li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-6">
                <div class="card product-detail-card">
                    <img src="uploads/<?php echo htmlspecialchars($product['image_path']); ?>" 
                         class="card-img-top product-image" alt="<?php echo htmlspecialchars($product['title']); ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="card product-detail-card">
                    <div class="card-body">
                        <h2 class="card-title"><?php echo htmlspecialchars($product['title']); ?></h2>
                        
                        <div class="mb-3">
                            <span class="badge bg-primary"><?php echo htmlspecialchars($product['category_name']); ?></span>
                        </div>

                        <div class="mb-3">
                            <h3 class="text-success">$<?php echo number_format($product['price'], 2); ?></h3>
                        </div>

                        <div class="mb-4">
                            <h5>Description</h5>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                        </div>

                        <div class="mb-4">
                            <h6><i class="fas fa-user"></i> Sold by: <?php echo htmlspecialchars($product['seller_name']); ?></h6>
                            <small class="text-muted">
                                <i class="fas fa-calendar"></i> 
                                Listed on <?php echo date('F j, Y', strtotime($product['created_at'])); ?>
                            </small>
                        </div>

                        <?php if ($user && $user['id'] != $product['seller_id']): ?>
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary btn-lg" onclick="addToCart(<?php echo $product['id']; ?>)">
                                <i class="fas fa-cart-plus"></i> Add to Cart
                            </button>
                            <?php if (!empty($userAddresses)): ?>
                            <div class="mb-3">
                                <label for="deliveryAddress" class="form-label">Delivery Address:</label>
                                <select class="form-select" id="deliveryAddress">
                                    <?php foreach ($userAddresses as $address): ?>
                                    <option value="<?php echo $address['id']; ?>" <?php echo $address['is_default'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($address['full_name']); ?> - 
                                        <?php echo htmlspecialchars($address['address_line1']); ?>, 
                                        <?php echo htmlspecialchars($address['city']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Payment Method:</label>
                                <div class="row g-2">
                                    <?php foreach ($paymentMethods as $payment): ?>
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="paymentMethod" 
                                                   id="payment_<?php echo $payment['id']; ?>" 
                                                   value="<?php echo $payment['id']; ?>"
                                                   <?php echo $payment['id'] == 1 ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="payment_<?php echo $payment['id']; ?>">
                                                <i class="<?php echo htmlspecialchars($payment['icon'] ?? 'fas fa-credit-card'); ?>"></i>
                                                <?php echo htmlspecialchars($payment['name']); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <button class="btn btn-success btn-lg" onclick="buyNow(<?php echo $product['id']; ?>)">
                                <i class="fas fa-shopping-bag"></i> Buy Now
                            </button>
                            <?php else: ?>
                            <a href="manage-addresses.php" class="btn btn-outline-success btn-lg">
                                <i class="fas fa-map-marker-alt"></i> Add Address to Buy
                            </a>
                            <?php endif; ?>
                        </div>
                        <?php elseif ($user && $user['id'] == $product['seller_id']): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> This is your own product listing.
                        </div>
                        <div class="d-grid gap-2">
                            <a href="edit-product.php?id=<?php echo $product['id']; ?>" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit Product
                            </a>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Please <a href="login.php">login</a> to purchase this item.
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <div class="row mt-5">
            <div class="col-12">
                <h4>Related Products</h4>
                <?php
                $relatedProducts = getAllProducts(4, 0, $product['category_id']);
                $relatedProducts = array_filter($relatedProducts, function($p) use ($product) {
                    return $p['id'] != $product['id'];
                });
                ?>
                <?php if (!empty($relatedProducts)): ?>
                <div class="row">
                    <?php foreach (array_slice($relatedProducts, 0, 3) as $relatedProduct): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card product-detail-card">
                            <img src="uploads/<?php echo htmlspecialchars($relatedProduct['image_path']); ?>" 
                                 class="card-img-top" alt="<?php echo htmlspecialchars($relatedProduct['title']); ?>" 
                                 style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h6 class="card-title"><?php echo htmlspecialchars($relatedProduct['title']); ?></h6>
                                <p class="card-text text-success"><strong>$<?php echo number_format($relatedProduct['price'], 2); ?></strong></p>
                                <a href="product-detail.php?id=<?php echo $relatedProduct['id']; ?>" class="btn btn-primary btn-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function addToCart(productId) {
            fetch('add-to-cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'product_id=' + productId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Product added to cart!');
                } else {
                    alert('Failed to add product to cart: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error adding product to cart');
            });
        }

        function buyNow(productId) {
            const addressId = document.getElementById('deliveryAddress').value;
            const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked');
            
            if (!addressId) {
                alert('Please select a delivery address.');
                return;
            }
            
            if (!paymentMethod) {
                alert('Please select a payment method.');
                return;
            }
            
            if (confirm('Are you sure you want to purchase this item?')) {
                fetch('purchase.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'product_id=' + productId + '&address_id=' + addressId + '&payment_method_id=' + paymentMethod.value
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Purchase successful! Check your purchase history.');
                        window.location.href = 'purchase-history.php';
                    } else {
                        alert('Failed to purchase: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error processing purchase');
                });
            }
        }
    </script>
</body>
</html>
