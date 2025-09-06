-- Sample Products for EcoFinds
-- Make sure you have users in the database first

-- First, let's create some sample users if they don't exist
INSERT IGNORE INTO users (username, email, password) VALUES
('john_doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('jane_smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('mike_wilson', 'mike@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('sarah_jones', 'sarah@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('alex_brown', 'alex@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Sample Products with random prices
INSERT INTO products (title, description, price, category_id, seller_id, image_path, status) VALUES

-- Electronics
('iPhone 12 Pro Max', 'Excellent condition iPhone 12 Pro Max, 128GB, Space Gray. Includes original charger and case.', 699.99, 1, 1, 'placeholder.svg', 'active'),
('MacBook Air M1', '2020 MacBook Air with M1 chip, 8GB RAM, 256GB SSD. Perfect for students and professionals.', 899.50, 1, 2, 'placeholder.svg', 'active'),
('Samsung Galaxy S21', 'Samsung Galaxy S21 5G, 128GB, Phantom Black. Lightly used, excellent condition.', 549.99, 1, 3, 'placeholder.svg', 'active'),
('iPad Pro 11-inch', 'iPad Pro 11-inch with Apple Pencil. Great for digital art and note-taking.', 799.00, 1, 4, 'placeholder.svg', 'active'),
('Sony WH-1000XM4 Headphones', 'Noise-cancelling wireless headphones. Excellent sound quality and battery life.', 249.99, 1, 5, 'placeholder.svg', 'active'),

-- Clothing
('Nike Air Max 270', 'Size 10 Nike Air Max 270 in white/black. Worn only a few times, like new condition.', 89.99, 2, 1, 'placeholder.svg', 'active'),
('Levi\'s 501 Jeans', 'Classic Levi\'s 501 jeans, size 32x34. Dark wash, excellent condition.', 45.00, 2, 2, 'placeholder.svg', 'active'),
('Adidas Hoodie', 'Black Adidas hoodie, size M. Soft and comfortable, perfect for casual wear.', 35.50, 2, 3, 'placeholder.svg', 'active'),
('North Face Jacket', 'North Face winter jacket, size L. Waterproof and warm, great for outdoor activities.', 125.99, 2, 4, 'placeholder.svg', 'active'),
('Converse Chuck Taylor', 'White Converse Chuck Taylor All Stars, size 9. Classic style, good condition.', 42.99, 2, 5, 'placeholder.svg', 'active'),

-- Books
('The Great Gatsby', 'F. Scott Fitzgerald classic novel. Paperback edition in good condition.', 8.99, 3, 1, 'placeholder.svg', 'active'),
('Python Programming Guide', 'Complete guide to Python programming for beginners. Includes exercises and examples.', 24.99, 3, 2, 'placeholder.svg', 'active'),
('Harry Potter and the Sorcerer\'s Stone', 'First book in the Harry Potter series. Hardcover edition, excellent condition.', 15.50, 3, 3, 'placeholder.svg', 'active'),
('To Kill a Mockingbird', 'Harper Lee\'s masterpiece. Paperback, well-maintained.', 12.99, 3, 4, 'placeholder.svg', 'active'),
('The Catcher in the Rye', 'J.D. Salinger classic. Paperback edition, good condition.', 9.99, 3, 5, 'placeholder.svg', 'active'),

-- Home & Garden
('IKEA Bookshelf', 'White IKEA Billy bookshelf, 5 shelves. Perfect for organizing books and decor.', 45.99, 4, 1, 'placeholder.svg', 'active'),
('Succulent Plant Collection', 'Set of 6 small succulent plants in decorative pots. Perfect for home decoration.', 28.99, 4, 2, 'placeholder.svg', 'active'),
('Coffee Table', 'Modern wooden coffee table, 48x24 inches. Clean lines and sturdy construction.', 89.99, 4, 3, 'placeholder.svg', 'active'),
('Garden Tools Set', 'Complete set of garden tools including shovel, rake, and pruning shears.', 35.00, 4, 4, 'placeholder.svg', 'active'),
('LED String Lights', 'Warm white LED string lights, 50 feet. Perfect for outdoor or indoor decoration.', 18.99, 4, 5, 'placeholder.svg', 'active'),

-- Sports & Fitness
('Yoga Mat', 'Premium yoga mat, 72 inches long. Non-slip surface, easy to clean.', 29.99, 5, 1, 'placeholder.svg', 'active'),
('Dumbbell Set', 'Pair of 20lb dumbbells. Perfect for home workouts and strength training.', 45.99, 5, 2, 'placeholder.svg', 'active'),
('Running Shoes', 'Nike running shoes, size 10.5. Lightweight and comfortable for long runs.', 79.99, 5, 3, 'placeholder.svg', 'active'),
('Basketball', 'Official size basketball, excellent condition. Great for outdoor games.', 25.00, 5, 4, 'placeholder.svg', 'active'),
('Resistance Bands Set', 'Set of 5 resistance bands with different resistance levels. Includes door anchor.', 22.99, 5, 5, 'placeholder.svg', 'active'),

-- Toys & Games
('LEGO Creator Set', 'LEGO Creator 3-in-1 set. Build a house, tree, or car. All pieces included.', 39.99, 6, 1, 'placeholder.svg', 'active'),
('Board Game Collection', 'Collection of 3 popular board games: Monopoly, Scrabble, and Risk.', 55.99, 6, 2, 'placeholder.svg', 'active'),
('Remote Control Car', 'RC car with 2.4GHz remote control. Fast and fun for kids and adults.', 34.99, 6, 3, 'placeholder.svg', 'active'),
('Puzzle Set', '1000-piece jigsaw puzzle featuring beautiful landscape. Great for relaxation.', 19.99, 6, 4, 'placeholder.svg', 'active'),
('Action Figure Collection', 'Set of 5 superhero action figures. Perfect for collectors or kids.', 28.99, 6, 5, 'placeholder.svg', 'active'),

-- Automotive
('Car Phone Mount', 'Magnetic car phone mount with wireless charging. Compatible with all phones.', 24.99, 7, 1, 'placeholder.svg', 'active'),
('Car Floor Mats', 'Set of 4 all-weather car floor mats. Universal fit for most vehicles.', 35.99, 7, 2, 'placeholder.svg', 'active'),
('Car Air Freshener', 'Premium car air freshener set with 6 different scents. Long-lasting fragrance.', 12.99, 7, 3, 'placeholder.svg', 'active'),
('Tire Pressure Gauge', 'Digital tire pressure gauge with LED display. Accurate and easy to use.', 18.99, 7, 4, 'placeholder.svg', 'active'),
('Car Cleaning Kit', 'Complete car cleaning kit with microfiber towels and cleaning solutions.', 29.99, 7, 5, 'placeholder.svg', 'active'),

-- Furniture
('Office Chair', 'Ergonomic office chair with lumbar support. Adjustable height and armrests.', 89.99, 8, 1, 'placeholder.svg', 'active'),
('Dining Table Set', 'Wooden dining table with 4 chairs. Perfect for small families or apartments.', 199.99, 8, 2, 'placeholder.svg', 'active'),
('Storage Ottoman', 'Large storage ottoman with removable lid. Perfect for extra seating and storage.', 65.99, 8, 3, 'placeholder.svg', 'active'),
('Desk Lamp', 'Adjustable LED desk lamp with USB charging port. Perfect for work or study.', 32.99, 8, 4, 'placeholder.svg', 'active'),
('Bookshelf', 'Tall wooden bookshelf with 6 shelves. Great for organizing books and display items.', 75.99, 8, 5, 'placeholder.svg', 'active'),

-- Jewelry
('Silver Necklace', 'Elegant silver necklace with pendant. Perfect for special occasions.', 45.99, 9, 1, 'placeholder.svg', 'active'),
('Gold Earrings', 'Classic gold hoop earrings. Timeless design, excellent condition.', 65.99, 9, 2, 'placeholder.svg', 'active'),
('Pearl Bracelet', 'Beautiful pearl bracelet with silver clasp. Elegant and sophisticated.', 85.99, 9, 3, 'placeholder.svg', 'active'),
('Diamond Ring', 'Vintage diamond ring, size 7. Perfect for engagement or special occasions.', 299.99, 9, 4, 'placeholder.svg', 'active'),
('Watch Collection', 'Set of 3 fashion watches with different colored bands. Great for any outfit.', 55.99, 9, 5, 'placeholder.svg', 'active'),

-- Other
('Vintage Camera', 'Film camera from the 1980s. Still works perfectly, great for photography enthusiasts.', 125.99, 10, 1, 'placeholder.svg', 'active'),
('Art Supplies Kit', 'Complete art supplies kit with paints, brushes, and canvas. Perfect for beginners.', 45.99, 10, 2, 'placeholder.svg', 'active'),
('Musical Instrument', 'Acoustic guitar in excellent condition. Perfect for learning or playing.', 199.99, 10, 3, 'placeholder.svg', 'active'),
('Craft Kit', 'DIY craft kit with materials to make handmade jewelry. Great for creative minds.', 28.99, 10, 4, 'placeholder.svg', 'active'),
('Collectible Figurine', 'Limited edition collectible figurine. Perfect for collectors and enthusiasts.', 75.99, 10, 5, 'placeholder.svg', 'active');
