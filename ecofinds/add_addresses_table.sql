-- Add addresses table to existing database
USE ecofinds;

-- Create addresses table
CREATE TABLE addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    address_line1 VARCHAR(200) NOT NULL,
    address_line2 VARCHAR(200),
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    country VARCHAR(100) NOT NULL DEFAULT 'United States',
    phone VARCHAR(20),
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Add delivery_address_id column to purchases table
ALTER TABLE purchases ADD COLUMN delivery_address_id INT NOT NULL DEFAULT 1 AFTER purchase_price;

-- Add foreign key constraint (we'll need to handle existing data)
-- For now, we'll add a default address for existing users
INSERT INTO addresses (user_id, full_name, address_line1, city, state, postal_code, country, phone, is_default)
SELECT DISTINCT id, username, '123 Main Street', 'Anytown', 'CA', '12345', 'United States', '555-0123', TRUE
FROM users;

-- Now we can add the foreign key constraint
ALTER TABLE purchases ADD FOREIGN KEY (delivery_address_id) REFERENCES addresses(id);
