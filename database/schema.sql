-- ============================================================
-- StockVision Database Schema
-- Version: 1.0.0
-- Engine: InnoDB | Charset: utf8mb4 | Collation: utf8mb4_unicode_ci
-- ============================================================

CREATE DATABASE IF NOT EXISTS `stockvision`
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE `stockvision`;

-- ============================================================
-- Users Table
-- ============================================================
CREATE TABLE `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `last_login` DATETIME NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Categories Table
-- ============================================================
CREATE TABLE `categories` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `color` VARCHAR(7) NULL COMMENT 'Hex color for charts/badges',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL COMMENT 'Soft delete timestamp',
    UNIQUE KEY `uk_categories_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Suppliers Table
-- ============================================================
CREATE TABLE `suppliers` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) NULL,
    `email` VARCHAR(255) NULL,
    `address` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL COMMENT 'Soft delete timestamp'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Products Table
-- ============================================================
CREATE TABLE `products` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `sku` VARCHAR(50) NOT NULL,
    `category_id` INT UNSIGNED NULL,
    `supplier_id` INT UNSIGNED NULL,
    `description` TEXT NULL,
    `unit_price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `quantity` INT NOT NULL DEFAULT 0,
    `min_stock_level` INT NOT NULL DEFAULT 10,
    `unit` VARCHAR(20) NOT NULL DEFAULT 'piece',
    `image` VARCHAR(255) NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME NULL COMMENT 'Soft delete timestamp',
    UNIQUE KEY `uk_products_sku` (`sku`),
    KEY `idx_products_category` (`category_id`),
    KEY `idx_products_supplier` (`supplier_id`),
    KEY `idx_products_quantity` (`quantity`, `min_stock_level`),
    CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`)
        REFERENCES `categories`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_products_supplier` FOREIGN KEY (`supplier_id`)
        REFERENCES `suppliers`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Stock Movements Table
-- ============================================================
CREATE TABLE `stock_movements` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `type` ENUM('in', 'out') NOT NULL,
    `quantity` INT NOT NULL,
    `quantity_before` INT NOT NULL,
    `quantity_after` INT NOT NULL,
    `notes` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY `idx_movements_product` (`product_id`),
    KEY `idx_movements_user` (`user_id`),
    KEY `idx_movements_type` (`type`),
    KEY `idx_movements_date` (`created_at`),
    CONSTRAINT `fk_movements_product` FOREIGN KEY (`product_id`)
        REFERENCES `products`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_movements_user` FOREIGN KEY (`user_id`)
        REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
