-- =============================================
-- AcuSport - Admin Migration
-- Run this SQL to add admin support
-- =============================================

-- Add is_admin column to users table
ALTER TABLE `users` ADD COLUMN `is_admin` TINYINT(1) NOT NULL DEFAULT 0 AFTER `cidade`;
