-- Update payment methods table
USE ecofinds;

-- Add missing columns
ALTER TABLE payment_methods ADD COLUMN icon VARCHAR(50) AFTER description;
ALTER TABLE payment_methods ADD COLUMN is_active BOOLEAN DEFAULT TRUE AFTER icon;
ALTER TABLE payment_methods ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER is_active;

-- Insert payment methods
INSERT IGNORE INTO payment_methods (name, description, icon, is_active) VALUES
('Cash on Delivery', 'Pay with cash when the item is delivered', 'fas fa-money-bill-wave', TRUE),
('Bank Transfer', 'Direct bank transfer to seller account', 'fas fa-university', TRUE),
('PayPal', 'Secure online payment via PayPal', 'fab fa-paypal', TRUE),
('Credit Card', 'Pay with credit or debit card', 'fas fa-credit-card', TRUE),
('Digital Wallet', 'Pay using digital wallet (Apple Pay, Google Pay)', 'fas fa-wallet', TRUE),
('Cryptocurrency', 'Pay with Bitcoin or other cryptocurrencies', 'fab fa-bitcoin', FALSE);

-- Add payment_method_id to purchases table
ALTER TABLE purchases ADD COLUMN payment_method_id INT AFTER delivery_address_id;

-- Add foreign key constraint
ALTER TABLE purchases ADD FOREIGN KEY (payment_method_id) REFERENCES payment_methods(id);

-- Set default payment method for existing purchases
UPDATE purchases SET payment_method_id = 1 WHERE payment_method_id IS NULL;
