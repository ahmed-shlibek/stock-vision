-- ============================================================
-- StockVision Seed Data
-- Comprehensive sample data for development
-- ============================================================

USE `stockvision`;

-- ============================================================
-- Users (password for all: the word shown in the comment)
-- Using pre-generated bcrypt hashes
-- ============================================================
INSERT INTO `users` (`name`, `email`, `password`, `role`, `is_active`, `last_login`) VALUES
('Ahmed Admin', 'admin@stockvision.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, NOW()),
('Sara Employee', 'employee@stockvision.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 1, NULL),
('Omar Viewer', 'viewer@stockvision.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'viewer', 1, NULL);
-- All passwords: admin123

-- ============================================================
-- Categories
-- ============================================================
INSERT INTO `categories` (`name`, `description`, `color`) VALUES
('Electronics', 'Electronic devices, gadgets, and accessories', '#6366f1'),
('Clothing', 'Apparel, shoes, and fashion accessories', '#ec4899'),
('Food & Beverages', 'Consumable food items and drinks', '#f59e0b'),
('Office Supplies', 'Stationery, paper, and office equipment', '#3b82f6'),
('Hardware', 'Tools, fasteners, and building materials', '#64748b'),
('Furniture', 'Desks, chairs, shelving, and fixtures', '#8b5cf6'),
('Sports', 'Sports equipment and outdoor gear', '#10b981'),
('Health & Beauty', 'Personal care, cosmetics, and wellness products', '#ef4444');

-- ============================================================
-- Suppliers
-- ============================================================
INSERT INTO `suppliers` (`name`, `phone`, `email`, `address`) VALUES
('TechNova Supplies', '+1-555-0101', 'orders@technova.com', '1200 Innovation Blvd, San Jose, CA 95134'),
('GlobalTrade Co.', '+1-555-0202', 'sales@globaltrade.com', '890 Commerce Ave, Chicago, IL 60601'),
('PrimeLine Distributors', '+1-555-0303', 'info@primeline.com', '450 Industrial Park Dr, Dallas, TX 75201'),
('Apex Wholesale', '+1-555-0404', 'contact@apexwholesale.com', '78 Market Street, New York, NY 10001'),
('EcoSource Materials', '+1-555-0505', 'supply@ecosource.com', '320 Green Way, Portland, OR 97201'),
('FastShip Logistics', '+1-555-0606', 'orders@fastship.com', '1500 Harbor Blvd, Miami, FL 33101');

-- ============================================================
-- Products (55+ across all categories)
-- ============================================================
INSERT INTO `products` (`name`, `sku`, `barcode`, `category_id`, `supplier_id`, `description`, `unit_price`, `quantity`, `min_stock_level`, `unit`) VALUES
-- Electronics (cat 1, supplier 1)
('Wireless Bluetooth Headphones', 'PRD-1001', '8901234001', 1, 1, 'Over-ear wireless headphones with ANC', 79.99, 45, 10, 'piece'),
('USB-C Charging Cable 2m', 'PRD-1002', '8901234002', 1, 1, 'Braided USB-C to USB-C cable', 12.99, 200, 50, 'piece'),
('Portable Power Bank 20000mAh', 'PRD-1003', '8901234003', 1, 1, 'Fast-charging portable battery', 34.99, 8, 15, 'piece'),
('Mechanical Keyboard RGB', 'PRD-1004', '8901234004', 1, 1, 'Full-size mechanical keyboard with Cherry MX', 129.99, 22, 10, 'piece'),
('Wireless Mouse Ergonomic', 'PRD-1005', '8901234005', 1, 1, 'Ergonomic vertical wireless mouse', 29.99, 60, 20, 'piece'),
('27" 4K Monitor', 'PRD-1006', '8901234006', 1, 1, 'IPS 4K UHD monitor 60Hz', 349.99, 5, 5, 'piece'),
('Webcam 1080p HD', 'PRD-1007', '8901234007', 1, 1, 'Full HD webcam with mic', 49.99, 0, 10, 'piece'),

-- Clothing (cat 2, supplier 2)
('Men\'s Cotton T-Shirt Black', 'PRD-2001', '8901234011', 2, 2, '100% organic cotton, crew neck', 19.99, 120, 30, 'piece'),
('Women\'s Running Shoes', 'PRD-2002', '8901234012', 2, 2, 'Lightweight breathable running shoes', 89.99, 35, 15, 'piece'),
('Denim Jeans Slim Fit', 'PRD-2003', '8901234013', 2, 2, 'Stretch denim, dark wash', 49.99, 75, 20, 'piece'),
('Winter Jacket Waterproof', 'PRD-2004', '8901234014', 2, 2, 'Insulated waterproof winter jacket', 129.99, 3, 10, 'piece'),
('Baseball Cap Classic', 'PRD-2005', '8901234015', 2, 2, 'Adjustable cotton baseball cap', 14.99, 90, 25, 'piece'),
('Sports Socks 6-Pack', 'PRD-2006', '8901234016', 2, 2, 'Moisture-wicking athletic socks', 12.99, 150, 40, 'pack'),
('Leather Belt Brown', 'PRD-2007', '8901234017', 2, 2, 'Genuine leather belt', 24.99, 40, 15, 'piece'),

-- Food & Beverages (cat 3, supplier 3)
('Organic Coffee Beans 1kg', 'PRD-3001', '8901234021', 3, 3, 'Single-origin Arabica coffee beans', 18.99, 60, 20, 'kg'),
('Green Tea Box 100 Bags', 'PRD-3002', '8901234022', 3, 3, 'Japanese green tea bags', 9.99, 4, 15, 'box'),
('Extra Virgin Olive Oil 500ml', 'PRD-3003', '8901234023', 3, 3, 'Cold-pressed Italian olive oil', 14.99, 45, 10, 'piece'),
('Dark Chocolate Bar 200g', 'PRD-3004', '8901234024', 3, 3, '70% cocoa dark chocolate', 4.99, 200, 50, 'piece'),
('Mineral Water 24-Pack', 'PRD-3005', '8901234025', 3, 3, 'Natural spring water 500ml bottles', 8.99, 100, 30, 'pack'),
('Protein Bars Box 12', 'PRD-3006', '8901234026', 3, 3, 'High-protein meal replacement bars', 24.99, 2, 10, 'box'),
('Honey Raw Organic 500g', 'PRD-3007', '8901234027', 3, 3, 'Pure raw organic honey', 12.99, 30, 10, 'piece'),

-- Office Supplies (cat 4, supplier 4)
('A4 Copy Paper Ream 500', 'PRD-4001', '8901234031', 4, 4, 'White A4 paper 80gsm', 6.99, 300, 50, 'ream'),
('Ballpoint Pens Pack 50', 'PRD-4002', '8901234032', 4, 4, 'Blue ink ballpoint pens', 9.99, 80, 20, 'pack'),
('Desk Organizer Wood', 'PRD-4003', '8901234033', 4, 4, 'Multi-compartment wooden desk organizer', 29.99, 18, 5, 'piece'),
('Whiteboard Markers Set 12', 'PRD-4004', '8901234034', 4, 4, 'Assorted colors dry-erase markers', 8.99, 55, 15, 'set'),
('Manila Folders 100-Pack', 'PRD-4005', '8901234035', 4, 4, 'Letter-size manila file folders', 14.99, 40, 10, 'pack'),
('Sticky Notes 3x3 Pack 12', 'PRD-4006', '8901234036', 4, 4, 'Yellow sticky notes pads', 5.99, 120, 30, 'pack'),
('Stapler Heavy Duty', 'PRD-4007', '8901234037', 4, 4, 'Desktop stapler 40-sheet capacity', 15.99, 12, 5, 'piece'),
('Paper Clips Box 1000', 'PRD-4008', '8901234038', 4, 4, 'Standard size paper clips', 3.99, 0, 10, 'box'),

-- Hardware (cat 5, supplier 5)
('Cordless Drill 20V', 'PRD-5001', '8901234041', 5, 5, 'Lithium-ion cordless drill with case', 89.99, 14, 5, 'piece'),
('Screwdriver Set 32pc', 'PRD-5002', '8901234042', 5, 5, 'Precision screwdriver set', 24.99, 25, 10, 'set'),
('LED Flashlight Rechargeable', 'PRD-5003', '8901234043', 5, 5, '1000 lumens tactical flashlight', 19.99, 40, 15, 'piece'),
('Measuring Tape 25ft', 'PRD-5004', '8901234044', 5, 5, 'Heavy-duty retractable measuring tape', 9.99, 55, 20, 'piece'),
('Safety Goggles', 'PRD-5005', '8901234045', 5, 5, 'Anti-fog safety glasses', 7.99, 70, 25, 'piece'),
('Duct Tape Silver 50yd', 'PRD-5006', '8901234046', 5, 5, 'Heavy-duty duct tape roll', 6.99, 0, 15, 'piece'),
('Work Gloves Leather', 'PRD-5007', '8901234047', 5, 5, 'Heavy-duty leather work gloves', 14.99, 32, 10, 'pair'),

-- Furniture (cat 6, supplier 6)
('Ergonomic Office Chair', 'PRD-6001', '8901234051', 6, 6, 'Adjustable lumbar support mesh chair', 299.99, 7, 3, 'piece'),
('Standing Desk Electric', 'PRD-6002', '8901234052', 6, 6, 'Electric height-adjustable desk 60x30', 499.99, 4, 2, 'piece'),
('Bookshelf 5-Tier', 'PRD-6003', '8901234053', 6, 6, 'Industrial metal and wood bookshelf', 89.99, 11, 5, 'piece'),
('Filing Cabinet 3-Drawer', 'PRD-6004', '8901234054', 6, 6, 'Metal vertical filing cabinet', 149.99, 6, 3, 'piece'),
('Monitor Arm Single', 'PRD-6005', '8901234055', 6, 6, 'Adjustable single monitor arm mount', 39.99, 20, 8, 'piece'),
('Desk Lamp LED', 'PRD-6006', '8901234056', 6, 6, 'Adjustable LED desk lamp with USB', 34.99, 28, 10, 'piece'),

-- Sports (cat 7, supplier 3)
('Yoga Mat Premium 6mm', 'PRD-7001', '8901234061', 7, 3, 'Non-slip TPE yoga mat', 29.99, 35, 10, 'piece'),
('Resistance Bands Set 5', 'PRD-7002', '8901234062', 7, 3, 'Latex resistance bands 5 levels', 19.99, 50, 15, 'set'),
('Water Bottle Insulated 1L', 'PRD-7003', '8901234063', 7, 3, 'Double-wall stainless steel bottle', 24.99, 65, 20, 'piece'),
('Jump Rope Speed', 'PRD-7004', '8901234064', 7, 3, 'Adjustable speed jump rope', 9.99, 40, 10, 'piece'),
('Dumbbell Set 20kg', 'PRD-7005', '8901234065', 7, 3, 'Adjustable dumbbell pair', 69.99, 1, 5, 'set'),
('Tennis Racket Pro', 'PRD-7006', '8901234066', 7, 3, 'Graphite composite tennis racket', 59.99, 15, 5, 'piece'),

-- Health & Beauty (cat 8, supplier 4)
('Sunscreen SPF 50 200ml', 'PRD-8001', '8901234071', 8, 4, 'Broad spectrum sunscreen lotion', 14.99, 80, 20, 'piece'),
('Hand Sanitizer 500ml', 'PRD-8002', '8901234072', 8, 4, '70% alcohol gel sanitizer', 6.99, 150, 40, 'piece'),
('Vitamin C Tablets 60', 'PRD-8003', '8901234073', 8, 4, '1000mg Vitamin C supplement', 12.99, 45, 15, 'piece'),
('Face Mask Sheet Pack 10', 'PRD-8004', '8901234074', 8, 4, 'Hydrating face mask sheets', 9.99, 60, 20, 'pack'),
('Electric Toothbrush', 'PRD-8005', '8901234075', 8, 4, 'Sonic electric toothbrush', 39.99, 0, 8, 'piece'),
('Shampoo Organic 300ml', 'PRD-8006', '8901234076', 8, 4, 'Sulfate-free organic shampoo', 11.99, 55, 15, 'piece'),
('First Aid Kit Basic', 'PRD-8007', '8901234077', 8, 4, 'Essential first aid supplies', 19.99, 22, 10, 'piece');

-- ============================================================
-- Stock Movements (30+ records)
-- ============================================================
INSERT INTO `stock_movements` (`product_id`, `user_id`, `type`, `quantity`, `quantity_before`, `quantity_after`, `notes`, `created_at`) VALUES
-- Recent stock-in movements
(1, 1, 'in', 50, 0, 50, 'Initial stock from TechNova shipment', DATE_SUB(NOW(), INTERVAL 30 DAY)),
(2, 1, 'in', 250, 0, 250, 'Bulk order USB cables', DATE_SUB(NOW(), INTERVAL 29 DAY)),
(3, 1, 'in', 20, 0, 20, 'Power bank restock', DATE_SUB(NOW(), INTERVAL 28 DAY)),
(8, 2, 'in', 150, 0, 150, 'T-shirt initial inventory', DATE_SUB(NOW(), INTERVAL 27 DAY)),
(14, 2, 'in', 80, 0, 80, 'Coffee beans monthly order', DATE_SUB(NOW(), INTERVAL 25 DAY)),
(22, 1, 'in', 350, 0, 350, 'Paper supply quarterly order', DATE_SUB(NOW(), INTERVAL 24 DAY)),
(29, 2, 'in', 20, 0, 20, 'Drill shipment from EcoSource', DATE_SUB(NOW(), INTERVAL 22 DAY)),
(36, 1, 'in', 10, 0, 10, 'Office chairs delivery', DATE_SUB(NOW(), INTERVAL 20 DAY)),
(42, 2, 'in', 50, 0, 50, 'Yoga mats restocked', DATE_SUB(NOW(), INTERVAL 18 DAY)),
(48, 1, 'in', 100, 0, 100, 'Sunscreen seasonal order', DATE_SUB(NOW(), INTERVAL 16 DAY)),

-- Stock-out movements
(1, 2, 'out', 5, 50, 45, 'Customer order #1042', DATE_SUB(NOW(), INTERVAL 15 DAY)),
(2, 2, 'out', 50, 250, 200, 'Wholesale distribution', DATE_SUB(NOW(), INTERVAL 14 DAY)),
(3, 1, 'out', 12, 20, 8, 'Online store sales batch', DATE_SUB(NOW(), INTERVAL 13 DAY)),
(8, 2, 'out', 30, 150, 120, 'Retail outlet fulfillment', DATE_SUB(NOW(), INTERVAL 12 DAY)),
(14, 1, 'out', 20, 80, 60, 'Cafe supply order', DATE_SUB(NOW(), INTERVAL 11 DAY)),
(22, 2, 'out', 50, 350, 300, 'Office restock distribution', DATE_SUB(NOW(), INTERVAL 10 DAY)),
(29, 1, 'out', 6, 20, 14, 'Hardware store order', DATE_SUB(NOW(), INTERVAL 9 DAY)),
(36, 2, 'out', 3, 10, 7, 'New office setup order', DATE_SUB(NOW(), INTERVAL 8 DAY)),
(42, 1, 'out', 15, 50, 35, 'Gym supply order', DATE_SUB(NOW(), INTERVAL 7 DAY)),
(48, 2, 'out', 20, 100, 80, 'Pharmacy distribution', DATE_SUB(NOW(), INTERVAL 6 DAY)),

-- More recent movements
(4, 1, 'in', 30, 0, 30, 'Keyboard restock', DATE_SUB(NOW(), INTERVAL 5 DAY)),
(4, 2, 'out', 8, 30, 22, 'IT department order', DATE_SUB(NOW(), INTERVAL 4 DAY)),
(9, 1, 'in', 40, 0, 40, 'Running shoes new model', DATE_SUB(NOW(), INTERVAL 4 DAY)),
(9, 2, 'out', 5, 40, 35, 'Retail store transfer', DATE_SUB(NOW(), INTERVAL 3 DAY)),
(15, 1, 'in', 10, 0, 10, 'Green tea emergency order', DATE_SUB(NOW(), INTERVAL 3 DAY)),
(15, 2, 'out', 6, 10, 4, 'Restaurant supply', DATE_SUB(NOW(), INTERVAL 2 DAY)),
(5, 2, 'in', 80, 0, 80, 'Wireless mouse bulk delivery', DATE_SUB(NOW(), INTERVAL 2 DAY)),
(5, 1, 'out', 20, 80, 60, 'Corporate client order', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(10, 2, 'in', 100, 0, 100, 'Denim jeans restock', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(10, 1, 'out', 25, 100, 75, 'Online sales batch', NOW()),
(20, 1, 'in', 5, 0, 5, 'Protein bars low stock refill', NOW()),
(20, 2, 'out', 3, 5, 2, 'Customer order', NOW()),
(11, 1, 'in', 8, 0, 8, 'Winter jacket pre-season', DATE_SUB(NOW(), INTERVAL 6 DAY)),
(11, 2, 'out', 5, 8, 3, 'Retail store allocation', DATE_SUB(NOW(), INTERVAL 2 DAY));

-- ============================================================
-- Activity Logs
-- ============================================================
INSERT INTO `activity_logs` (`user_id`, `action`, `entity_type`, `entity_id`, `description`, `ip_address`, `created_at`) VALUES
(1, 'user.login', 'user', 1, 'Admin logged in', '127.0.0.1', DATE_SUB(NOW(), INTERVAL 30 DAY)),
(1, 'category.created', 'category', 1, 'Created category: Electronics', '127.0.0.1', DATE_SUB(NOW(), INTERVAL 30 DAY)),
(1, 'category.created', 'category', 2, 'Created category: Clothing', '127.0.0.1', DATE_SUB(NOW(), INTERVAL 30 DAY)),
(1, 'supplier.created', 'supplier', 1, 'Created supplier: TechNova Supplies', '127.0.0.1', DATE_SUB(NOW(), INTERVAL 29 DAY)),
(1, 'product.created', 'product', 1, 'Created product: Wireless Bluetooth Headphones (PRD-1001)', '127.0.0.1', DATE_SUB(NOW(), INTERVAL 28 DAY)),
(2, 'user.login', 'user', 2, 'Employee logged in', '127.0.0.1', DATE_SUB(NOW(), INTERVAL 27 DAY)),
(2, 'product.created', 'product', 8, 'Created product: Men\'s Cotton T-Shirt Black (PRD-2001)', '127.0.0.1', DATE_SUB(NOW(), INTERVAL 27 DAY)),
(1, 'stock.in', 'product', 1, 'Added 50 units to Wireless Bluetooth Headphones', '127.0.0.1', DATE_SUB(NOW(), INTERVAL 25 DAY)),
(2, 'stock.out', 'product', 1, 'Removed 5 units from Wireless Bluetooth Headphones', '127.0.0.1', DATE_SUB(NOW(), INTERVAL 15 DAY)),
(1, 'product.updated', 'product', 4, 'Updated product: Mechanical Keyboard RGB', '127.0.0.1', DATE_SUB(NOW(), INTERVAL 10 DAY)),
(1, 'supplier.updated', 'supplier', 3, 'Updated supplier: PrimeLine Distributors', '127.0.0.1', DATE_SUB(NOW(), INTERVAL 8 DAY)),
(2, 'stock.in', 'product', 5, 'Added 80 units to Wireless Mouse Ergonomic', '127.0.0.1', DATE_SUB(NOW(), INTERVAL 2 DAY)),
(1, 'stock.out', 'product', 5, 'Removed 20 units from Wireless Mouse Ergonomic', '127.0.0.1', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(1, 'user.login', 'user', 1, 'Admin logged in', '127.0.0.1', NOW()),
(2, 'stock.out', 'product', 20, 'Removed 3 units from Protein Bars Box 12', '127.0.0.1', NOW());
