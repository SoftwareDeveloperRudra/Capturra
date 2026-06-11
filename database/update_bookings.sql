-- Add missing columns to bookings table for payment and event tracking
-- Run this SQL in phpMyAdmin

ALTER TABLE `bookings` 
ADD COLUMN `event_type` VARCHAR(50) DEFAULT 'Others' AFTER `status`,
ADD COLUMN `payment_id` VARCHAR(100) DEFAULT NULL AFTER `event_type`,
ADD COLUMN `advance_amount` DECIMAL(10,2) DEFAULT 0 AFTER `payment_id`,
ADD COLUMN `total_amount` DECIMAL(10,2) DEFAULT 0 AFTER `advance_amount`;

-- Create payments table for tracking all transactions
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` VARCHAR(20) DEFAULT 'razorpay',
  `payment_id` VARCHAR(100) DEFAULT NULL,
  `status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;