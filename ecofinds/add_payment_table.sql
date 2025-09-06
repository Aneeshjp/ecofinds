-- Add payment methods table to existing database
USE ecofinds;

-- Create payment methods table
CREATE TABLE payment_methods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Add payment_method_id column to purchases table
ALTER TABLE purchases ADD COLUMN payment_method_id INT AFTER delivery_address_id;

-- Insert default payment methods
INSERT INTO payment_methods (name, description, icon, is_active) VALUES
('Cash on Delivery', 'Pay with cash when the item is delivered', 'fas fa-money-bill-wave', TRUE),
('Bank Transfer', 'Direct bank transfer to seller account', 'fas fa-university', TRUE),
('PayPal', 'Secure online payment via PayPal', 'fab fa-paypal', TRUE),
('Credit Card', 'Pay with credit or debit card', 'fas fa-credit-card', TRUE),
('Digital Wallet', 'Pay using digital wallet (Apple Pay, Google Pay)', 'fas fa-wallet', TRUE),
('Cryptocurrency', 'Pay with Bitcoin or other cryptocurrencies', 'fab fa-bitcoin', FALSE);

-- Add foreign key constraint
ALTER TABLE purchases ADD FOREIGN KEY (payment_method_id) REFERENCES payment_methods(id);

-- Update existing purchases with default payment method (Cash on Delivery)
UPDATE purchases SET payment_method_id = 1 WHERE payment_method_id IS NULL;
