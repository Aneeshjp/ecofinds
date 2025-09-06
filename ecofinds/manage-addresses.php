<?php
require_once 'includes/auth.php';
require_once 'includes/addresses.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$user = getCurrentUser();
$addresses = getUserAddresses($user['id']);
$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $fullName = trim($_POST['full_name']);
    $addressLine1 = trim($_POST['address_line1']);
    $addressLine2 = trim($_POST['address_line2']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $postalCode = trim($_POST['postal_code']);
    $country = trim($_POST['country']);
    $phone = trim($_POST['phone']);
    $isDefault = isset($_POST['is_default']);
    
    // Validation
    if (empty($fullName) || empty($addressLine1) || empty($city) || empty($state) || empty($postalCode)) {
        $error = 'Please fill in all required fields.';
    } else {
        switch ($action) {
            case 'add':
                if (createAddress($user['id'], $fullName, $addressLine1, $addressLine2, $city, $state, $postalCode, $country, $phone, $isDefault)) {
                    $success = 'Address added successfully!';
                } else {
                    $error = 'Failed to add address.';
                }
                break;
            case 'edit':
                $addressId = intval($_POST['address_id']);
                if (updateAddress($addressId, $user['id'], $fullName, $addressLine1, $addressLine2, $city, $state, $postalCode, $country, $phone, $isDefault)) {
                    $success = 'Address updated successfully!';
                } else {
                    $error = 'Failed to update address.';
                }
                break;
            case 'delete':
                $addressId = intval($_POST['address_id']);
                if (deleteAddress($addressId, $user['id'])) {
                    $success = 'Address deleted successfully!';
                } else {
                    $error = 'Failed to delete address.';
                }
                break;
        }
        
        // Refresh addresses
        $addresses = getUserAddresses($user['id']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Addresses - EcoFinds</title>
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
        .address-card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .address-card:hover {
            transform: translateY(-2px);
        }
        .default-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-map-marker-alt"></i> Manage Addresses</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                        <i class="fas fa-plus"></i> Add New Address
                    </button>
                </div>

                <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>

                <?php if ($success): ?>
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                </div>
                <?php endif; ?>

                <?php if (empty($addresses)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-map-marker-alt fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No addresses yet</h4>
                    <p class="text-muted">Add your first delivery address to start shopping!</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                        <i class="fas fa-plus"></i> Add Address
                    </button>
                </div>
                <?php else: ?>
                <div class="row">
                    <?php foreach ($addresses as $address): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card address-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h6 class="card-title"><?php echo htmlspecialchars($address['full_name']); ?></h6>
                                    <?php if ($address['is_default']): ?>
                                    <span class="badge default-badge">Default</span>
                                    <?php endif; ?>
                                </div>
                                <p class="card-text">
                                    <?php echo htmlspecialchars($address['address_line1']); ?><br>
                                    <?php if ($address['address_line2']): ?>
                                    <?php echo htmlspecialchars($address['address_line2']); ?><br>
                                    <?php endif; ?>
                                    <?php echo htmlspecialchars($address['city']); ?>, <?php echo htmlspecialchars($address['state']); ?> <?php echo htmlspecialchars($address['postal_code']); ?><br>
                                    <?php echo htmlspecialchars($address['country']); ?>
                                </p>
                                <?php if ($address['phone']): ?>
                                <p class="card-text">
                                    <i class="fas fa-phone"></i> <?php echo htmlspecialchars($address['phone']); ?>
                                </p>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer">
                                <div class="btn-group w-100" role="group">
                                    <button class="btn btn-outline-primary btn-sm" onclick="editAddress(<?php echo $address['id']; ?>)">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" onclick="deleteAddress(<?php echo $address['id']; ?>)">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Add Address Modal -->
    <div class="modal fade" id="addAddressModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="full_name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="address_line1" class="form-label">Address Line 1 *</label>
                            <input type="text" class="form-control" id="address_line1" name="address_line1" required>
                        </div>
                        <div class="mb-3">
                            <label for="address_line2" class="form-label">Address Line 2</label>
                            <input type="text" class="form-control" id="address_line2" name="address_line2">
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">City *</label>
                                <input type="text" class="form-control" id="city" name="city" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="state" class="form-label">State *</label>
                                <input type="text" class="form-control" id="state" name="state" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="postal_code" class="form-label">Postal Code *</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="country" name="country" value="United States">
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_default" name="is_default">
                            <label class="form-check-label" for="is_default">
                                Set as default address
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Address</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Address Modal -->
    <div class="modal fade" id="editAddressModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="address_id" id="edit_address_id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_full_name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="edit_full_name" name="full_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="edit_phone" name="phone">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_address_line1" class="form-label">Address Line 1 *</label>
                            <input type="text" class="form-control" id="edit_address_line1" name="address_line1" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_address_line2" class="form-label">Address Line 2</label>
                            <input type="text" class="form-control" id="edit_address_line2" name="address_line2">
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="edit_city" class="form-label">City *</label>
                                <input type="text" class="form-control" id="edit_city" name="city" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="edit_state" class="form-label">State *</label>
                                <input type="text" class="form-control" id="edit_state" name="state" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="edit_postal_code" class="form-label">Postal Code *</label>
                                <input type="text" class="form-control" id="edit_postal_code" name="postal_code" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="edit_country" name="country">
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit_is_default" name="is_default">
                            <label class="form-check-label" for="edit_is_default">
                                Set as default address
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Address</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this address? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="address_id" id="delete_address_id">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editAddress(addressId) {
            // Get address data from the page
            const addressCard = document.querySelector(`[onclick="editAddress(${addressId})"]`).closest('.card');
            const fullName = addressCard.querySelector('.card-title').textContent.trim();
            const addressText = addressCard.querySelector('.card-text').textContent.trim();
            
            // Parse address data (this is a simplified version)
            document.getElementById('edit_address_id').value = addressId;
            document.getElementById('edit_full_name').value = fullName;
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('editAddressModal'));
            modal.show();
        }
        
        function deleteAddress(addressId) {
            document.getElementById('delete_address_id').value = addressId;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }
    </script>
</body>
</html>
