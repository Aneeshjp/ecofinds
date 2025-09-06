<?php
require_once 'includes/auth.php';
require_once 'includes/cart.php';
require_once 'includes/addresses.php';
require_once 'includes/payments.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$user = getCurrentUser();
$cartItems = getCartItems($user['id']);
$cartTotal = getCartTotal($user['id']);
$userAddresses = getUserAddresses($user['id']);
$paymentMethods = getAllPaymentMethods();
$message = '';

// Handle cart updates
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $productId = intval($_POST['product_id']);
        
        switch ($_POST['action']) {
            case 'update':
                $quantity = intval($_POST['quantity']);
                if (updateCartQuantity($user['id'], $productId, $quantity)) {
                    $message = 'Cart updated successfully!';
                } else {
                    $message = 'Failed to update cart.';
                }
                break;
            case 'remove':
                if (removeFromCart($user['id'], $productId)) {
                    $message = 'Item removed from cart!';
                } else {
                    $message = 'Failed to remove item.';
                }
                break;
            case 'clear':
                if (clearCart($user['id'])) {
                    $message = 'Cart cleared successfully!';
                } else {
                    $message = 'Failed to clear cart.';
                }
                break;
        }
        
        // Refresh cart data
        $cartItems = getCartItems($user['id']);
        $cartTotal = getCartTotal($user['id']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - EcoFinds</title>
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
        .cart-item {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .cart-summary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
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
                        <a class="nav-link active" href="cart.php">
                            <i class="fas fa-shopping-cart"></i> Cart
                            <?php if (count($cartItems) > 0): ?>
                            <span class="badge bg-danger"><?php echo count($cartItems); ?></span>
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
        <div class="row">
            <div class="col-12">
                <h2><i class="fas fa-shopping-cart"></i> Shopping Cart</h2>
                
                <?php if ($message): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if (empty($cartItems)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Your cart is empty</h4>
                    <p class="text-muted">Start shopping to add items to your cart!</p>
                    <a href="products.php" class="btn btn-primary">
                        <i class="fas fa-shopping-bag"></i> Browse Products
                    </a>
                </div>
                <?php else: ?>
                <div class="row">
                    <div class="col-md-8">
                        <?php foreach ($cartItems as $item): ?>
                        <div class="card cart-item mb-3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <img src="uploads/<?php echo htmlspecialchars($item['image_path']); ?>" 
                                             class="img-fluid rounded" alt="<?php echo htmlspecialchars($item['title']); ?>"
                                             style="height: 80px; object-fit: cover;">
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="mb-1"><?php echo htmlspecialchars($item['title']); ?></h6>
                                        <p class="text-muted small mb-1"><?php echo htmlspecialchars($item['seller_name']); ?></p>
                                        <p class="text-success mb-0"><strong>$<?php echo number_format($item['price'], 2); ?></strong></p>
                                    </div>
                                    <div class="col-md-3">
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                            <div class="input-group input-group-sm">
                                                <input type="number" class="form-control" name="quantity" 
                                                       value="<?php echo $item['quantity']; ?>" min="1" max="99">
                                                <button class="btn btn-outline-primary" type="submit">
                                                    <i class="fas fa-sync"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-2">
                                        <p class="mb-1"><strong>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></strong></p>
                                    </div>
                                    <div class="col-md-1">
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="action" value="remove">
                                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                    onclick="return confirm('Remove this item from cart?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>

                        <div class="d-flex justify-content-between">
                            <a href="products.php" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left"></i> Continue Shopping
                            </a>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="action" value="clear">
                                <button type="submit" class="btn btn-outline-danger" 
                                        onclick="return confirm('Clear entire cart?')">
                                    <i class="fas fa-trash"></i> Clear Cart
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card cart-summary">
                            <div class="card-body">
                                <h5 class="card-title">Order Summary</h5>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Items (<?php echo count($cartItems); ?>):</span>
                                    <span>$<?php echo number_format($cartTotal, 2); ?></span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <strong>Total:</strong>
                                    <strong>$<?php echo number_format($cartTotal, 2); ?></strong>
                                </div>
                                
                                <!-- Delivery Address Selection -->
                                <div class="mb-3">
                                    <h6><i class="fas fa-map-marker-alt"></i> Delivery Address</h6>
                                    <?php if (empty($userAddresses)): ?>
                                    <div class="alert alert-warning">
                                        <small>No delivery address found. <a href="manage-addresses.php" class="alert-link">Add an address</a> to continue.</small>
                                    </div>
                                    <?php else: ?>
                                    <select class="form-select form-select-sm" id="deliveryAddress">
                                        <?php foreach ($userAddresses as $address): ?>
                                        <option value="<?php echo $address['id']; ?>" <?php echo $address['is_default'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($address['full_name']); ?> - 
                                            <?php echo htmlspecialchars($address['address_line1']); ?>, 
                                            <?php echo htmlspecialchars($address['city']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="text-light">
                                        <a href="manage-addresses.php" class="text-light">Manage addresses</a>
                                    </small>
                                    <?php endif; ?>
                                </div>

                                <!-- Payment Method Selection -->
                                <div class="mb-3">
                                    <h6><i class="fas fa-credit-card"></i> Payment Method</h6>
                                    <div class="row g-2">
                                        <?php foreach ($paymentMethods as $payment): ?>
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="paymentMethod" 
                                                       id="payment_<?php echo $payment['id']; ?>" 
                                                       value="<?php echo $payment['id']; ?>"
                                                       <?php echo $payment['id'] == 1 ? 'checked' : ''; ?>>
                                                <label class="form-check-label text-light" for="payment_<?php echo $payment['id']; ?>">
                                                    <i class="<?php echo htmlspecialchars($payment['icon'] ?? 'fas fa-credit-card'); ?>"></i>
                                                    <?php echo htmlspecialchars($payment['name']); ?>
                                                </label>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <small class="text-light">
                                        <i class="fas fa-info-circle"></i> 
                                        Payment details will be shared with the seller after purchase
                                    </small>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <?php if (!empty($userAddresses)): ?>
                                    <button class="btn btn-light btn-lg" onclick="checkout()">
                                        <i class="fas fa-credit-card"></i> Checkout
                                    </button>
                                    <?php else: ?>
                                    <a href="manage-addresses.php" class="btn btn-outline-light btn-lg">
                                        <i class="fas fa-plus"></i> Add Address First
                                    </a>
                                    <?php endif; ?>
                                </div>
                                <small class="text-light mt-2 d-block">
                                    <i class="fas fa-info-circle"></i> 
                                    You'll be redirected to complete your purchase
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function checkout() {
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
            
            if (confirm('Proceed to checkout? This will purchase all items in your cart.')) {
                fetch('checkout.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=checkout&address_id=' + addressId + '&payment_method_id=' + paymentMethod.value
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Purchase successful! Check your purchase history.');
                        window.location.href = 'purchase-history.php';
                    } else {
                        alert('Failed to complete purchase: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error processing checkout');
                });
            }
        }
    </script>
</body>
</html>
