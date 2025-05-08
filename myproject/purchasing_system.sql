-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2025 at 07:47 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `purchasing_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `inventory_items`
--

CREATE TABLE `inventory_items` (
  `id` int(11) NOT NULL,
  `item_code` varchar(50) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `reorder_point` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_stock`
--

CREATE TABLE `inventory_stock` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity_received` int(11) NOT NULL,
  `unit_cost` decimal(10,2) DEFAULT NULL,
  `transaction_date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item_file`
--

CREATE TABLE `item_file` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `unit_cost` decimal(10,2) DEFAULT NULL,
  `required_inventory` int(11) DEFAULT NULL,
  `reorder_point` int(11) DEFAULT NULL,
  `product_type` varchar(100) DEFAULT NULL,
  `track_inventory` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_file`
--

INSERT INTO `item_file` (`id`, `code`, `name`, `unit_cost`, `required_inventory`, `reorder_point`, `product_type`, `track_inventory`) VALUES
(1, 'ITEM001', 'prince', '2858.00', 3, 100, 'Good', 1),
(2, 'ITEM001', 'prince', '2858.00', 3, 100, 'Service', 1),
(3, 'ITEM001', 'prince', '2858.00', 3, 100, '', 0),
(4, 'ITEM002', 'prince', '2858.00', 3, 100, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `material_request`
--

CREATE TABLE `material_request` (
  `id` int(11) NOT NULL,
  `company` varchar(100) DEFAULT NULL,
  `requestor_name` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `reason` text,
  `due_date` date DEFAULT NULL,
  `date` date DEFAULT NULL,
  `section` varchar(100) DEFAULT NULL,
  `mrf` varchar(50) DEFAULT NULL,
  `po_number` varchar(50) DEFAULT NULL,
  `for_costing` tinyint(1) DEFAULT NULL,
  `for_purchase` tinyint(1) DEFAULT NULL,
  `prepared_by` varchar(100) DEFAULT NULL,
  `approved_by_dept` varchar(100) DEFAULT NULL,
  `received_by` varchar(100) DEFAULT NULL,
  `approved_by_purchasing` varchar(100) DEFAULT NULL,
  `grand_total` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `material_request`
--

INSERT INTO `material_request` (`id`, `company`, `requestor_name`, `department`, `reason`, `due_date`, `date`, `section`, `mrf`, `po_number`, `for_costing`, `for_purchase`, `prepared_by`, `approved_by_dept`, `received_by`, `approved_by_purchasing`, `grand_total`, `created_at`) VALUES
(1, 'TRACKERTEER WEB DEVELOPMENT CORP.', 'pj', 'asd', '', '2025-05-03', '2025-04-28', 'asd', 'a', '1', 1, 0, 'a', 'a', 'a', 'a', '1504.00', '2025-04-28 01:26:49'),
(2, 'BW MANUFACTURING CORP.', 'pj', 'asd', '', '2025-04-30', '2025-04-28', 'asd', 'a', '1', 1, 0, 'a', 'a', 'a', 'a', '2848.00', '2025-04-28 01:31:38'),
(3, 'BW MANUFACTURING CORP.', 'pj', 'asd', '', '2025-05-01', '2025-04-28', 'asd', 'a', '1', 0, 0, 'a', 'a', 'a', 'a', '4.00', '2025-04-28 01:33:48');

-- --------------------------------------------------------

--
-- Table structure for table `material_request_items`
--

CREATE TABLE `material_request_items` (
  `id` int(11) NOT NULL,
  `request_id` int(11) DEFAULT NULL,
  `item_code` varchar(100) DEFAULT NULL,
  `description` text,
  `stocks` int(11) DEFAULT NULL,
  `consumption` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `material_request_items`
--

INSERT INTO `material_request_items` (`id`, `request_id`, `item_code`, `description`, `stocks`, `consumption`, `quantity`, `unit_price`, `total_price`) VALUES
(1, 1, 'a', 'a', 2, 2111212, 2, '2.00', '4.00'),
(2, 1, 'a', 'a', 15, 20, 3, '500.00', '1500.00'),
(3, 2, 'a', 'a', 2, 2111212, 2, '2.00', '4.00'),
(4, 2, 'a', 'a', 15, 20, 3, '500.00', '1500.00'),
(5, 2, '111', 'a', 2, 4, 21, '64.00', '1344.00'),
(6, 3, 'a', 'a', 2, 2111212, 2, '2.00', '4.00');

-- --------------------------------------------------------

--
-- Table structure for table `mrr`
--

CREATE TABLE `mrr` (
  `id` int(11) NOT NULL,
  `po_id` int(11) NOT NULL,
  `mrr_no` varchar(50) NOT NULL,
  `received_date` date NOT NULL,
  `received_by` varchar(100) NOT NULL,
  `si_no` varchar(100) DEFAULT NULL,
  `si_document` varchar(255) DEFAULT NULL,
  `dr_no` varchar(100) DEFAULT NULL,
  `dr_personel` varchar(100) DEFAULT NULL,
  `dr_docs` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mrr`
--

INSERT INTO `mrr` (`id`, `po_id`, `mrr_no`, `received_date`, `received_by`, `si_no`, `si_document`, `dr_no`, `dr_personel`, `dr_docs`, `created_at`) VALUES
(15, 1, 'a', '2025-05-07', 'a', NULL, NULL, 'a', 'a', NULL, '2025-05-07 01:27:18'),
(16, 1, 'a', '2025-05-07', 'a', NULL, NULL, '1', 'a', NULL, '2025-05-07 01:36:29'),
(17, 1, 'a', '2025-05-07', 'a', NULL, NULL, 'a', 'a', NULL, '2025-05-07 01:38:07'),
(18, 1, 'a', '2025-05-07', 'a', NULL, NULL, 'a', 'a', NULL, '2025-05-07 01:46:57'),
(19, 1, 'a', '2025-05-07', 'a', NULL, NULL, 'a', 'a', NULL, '2025-05-07 01:49:28'),
(20, 1, 'a', '2025-05-07', 'a', NULL, NULL, '1', 'aa', NULL, '2025-05-07 03:39:25'),
(21, 2, 'a', '2025-05-07', 'a', 'a', NULL, 'a', 'a', NULL, '2025-05-07 05:17:01'),
(22, 2, 'pj', '2025-05-07', 'pj', 'pj', NULL, 'pj', 'pj', NULL, '2025-05-07 05:19:09');

-- --------------------------------------------------------

--
-- Table structure for table `mrr_transactions`
--

CREATE TABLE `mrr_transactions` (
  `id` int(11) NOT NULL,
  `mrr_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `received_qty` int(11) NOT NULL,
  `remarks` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mrr_transactions`
--

INSERT INTO `mrr_transactions` (`id`, `mrr_id`, `item_id`, `received_qty`, `remarks`) VALUES
(1, 15, 4, 9, ''),
(2, 15, 9, 11, ''),
(3, 16, 4, 2, ''),
(4, 16, 9, 2, ''),
(5, 17, 4, 1, ''),
(6, 17, 9, 2, ''),
(7, 18, 4, 2, 'a'),
(8, 18, 9, 2, 'a'),
(9, 19, 4, 1, 'a'),
(10, 19, 9, 1, 'a'),
(11, 20, 4, 2, ''),
(12, 20, 9, 3, ''),
(13, 21, 5, 2, ''),
(14, 21, 10, 2, ''),
(15, 21, 11, 19, ''),
(16, 21, 12, 0, ''),
(17, 22, 5, 3, ''),
(18, 22, 10, 12, ''),
(19, 22, 11, 26, ''),
(20, 22, 12, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` int(11) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `address` text,
  `contact_no` varchar(50) DEFAULT NULL,
  `email_address` varchar(100) DEFAULT NULL,
  `tin_no` varchar(50) DEFAULT NULL,
  `attention` varchar(100) DEFAULT NULL,
  `po_no` varchar(50) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `mrf` varchar(50) DEFAULT NULL,
  `terms` varchar(100) DEFAULT NULL,
  `incoterms` varchar(100) DEFAULT NULL,
  `ship_mode` varchar(50) DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `grand_total` decimal(12,2) DEFAULT NULL,
  `prepared_by` varchar(100) DEFAULT NULL,
  `approved_by_dept` varchar(100) DEFAULT NULL,
  `received_by` varchar(100) DEFAULT NULL,
  `approved_by_purchasing` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(50) DEFAULT 'Pending',
  `po_status` enum('Open','Partially Received','Closed') DEFAULT 'Open'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `company`, `address`, `contact_no`, `email_address`, `tin_no`, `attention`, `po_no`, `order_date`, `mrf`, `terms`, `incoterms`, `ship_mode`, `delivery_date`, `grand_total`, `prepared_by`, `approved_by_dept`, `received_by`, `approved_by_purchasing`, `created_at`, `status`, `po_status`) VALUES
(1, 'BW MANUFACTURING CORP.', 'Banaba, Dapdap', '1', '123@gmail.com', '11', '1', 'PO#3', '2025-05-07', 'a', '1', '11', 'sea', '2025-05-07', '150.00', 'a', 'a', 'a', 'a', '2025-05-07 00:01:11', 'Pending', 'Open'),
(2, 'CRACKERJACK RECRUITMENT AGENCY CORP.', 'a', '090909999', 'me', 'a', 'a', 'a', '2025-05-07', 'a', 'a', 'a', 'sea', '2025-05-07', '564.00', '', '', '', '', '2025-05-07 05:16:19', 'Closed', 'Open');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` int(11) NOT NULL,
  `po_id` int(11) DEFAULT NULL,
  `item` varchar(255) DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL,
  `description` text,
  `quantity` int(11) DEFAULT NULL,
  `uom` varchar(50) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Open'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_order_items`
--

INSERT INTO `purchase_order_items` (`id`, `po_id`, `item`, `code`, `description`, `quantity`, `uom`, `unit_price`, `total`, `status`) VALUES
(1, 8, 'a', '1', 'a', 3, NULL, '6.00', NULL, 'Open'),
(2, 8, 'a', '1', 'a', 13, NULL, '2131.00', NULL, 'Open'),
(3, 9, 'pj', '1', 'a', 3, NULL, '500.00', NULL, 'Open'),
(4, 1, 'pj', '1', 'a', 3, NULL, '500.00', NULL, 'Closed'),
(5, 2, 'PRINCE', '1', 'a', 3, NULL, '500.00', NULL, 'Closed'),
(6, 3, 'PRINCE', '1', 'a', 3, NULL, '500.00', NULL, 'Closed'),
(7, 4, 'Ballpen', '123', 'Black', 10, NULL, '15.00', NULL, 'Closed'),
(8, 4, 'Ballpen', '1234', 'box', 10, NULL, '20.00', NULL, 'Closed'),
(9, 1, 'Ballpen', '123', 'Black', 10, NULL, '15.00', NULL, 'Closed'),
(10, 2, 'a', 'a', 'a', 12, NULL, '9.00', NULL, 'Closed'),
(11, 2, 'b', 'b', 'a', 19, NULL, '24.00', NULL, 'Closed'),
(12, 2, '', '', 'b', 0, NULL, '0.00', NULL, 'Closed');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requests`
--

CREATE TABLE `purchase_requests` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `urgency` enum('Low','Medium','High') NOT NULL,
  `department` varchar(100) NOT NULL,
  `supporting_document` varchar(255) DEFAULT NULL,
  `submission_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `submitted_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_requests`
--

INSERT INTO `purchase_requests` (`id`, `item_name`, `quantity`, `urgency`, `department`, `supporting_document`, `submission_date`, `submitted_at`) VALUES
(1, 'PRINCE', 1, 'Medium', 'Controller', '', '2025-05-08 02:30:39', '2025-05-08 10:30:39');

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `id` int(11) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `requestor_name` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `reason` text,
  `due_date` date DEFAULT NULL,
  `date` date DEFAULT NULL,
  `section` varchar(255) DEFAULT NULL,
  `mrf` varchar(255) DEFAULT NULL,
  `po_number` varchar(100) DEFAULT NULL,
  `for_costing` tinyint(1) DEFAULT '0',
  `for_purchase` tinyint(1) DEFAULT '0',
  `prepared_by` varchar(100) DEFAULT NULL,
  `approved_by_dept` varchar(100) DEFAULT NULL,
  `received_by` varchar(100) DEFAULT NULL,
  `approved_by_purchasing` varchar(100) DEFAULT NULL,
  `grand_total` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `service_requests`
--

INSERT INTO `service_requests` (`id`, `company`, `requestor_name`, `department`, `reason`, `due_date`, `date`, `section`, `mrf`, `po_number`, `for_costing`, `for_purchase`, `prepared_by`, `approved_by_dept`, `received_by`, `approved_by_purchasing`, `grand_total`, `created_at`) VALUES
(1, 'BW MANUFACTURING CORP.', 'pj', 'asd', 'a', '2025-04-30', '2025-04-30', 'asd', 'a', '1', 0, 0, 'a', 'a', 'a', 'a', '6.00', '2025-04-30 01:28:55'),
(2, 'TRACKERTEER WEB DEVELOPMENT CORP.', 'pj', 'asd', '', '2025-04-30', '2025-04-30', 'asd', 'a', '1', 0, 0, 'a', 'a', 'a', 'a', '4.00', '2025-04-30 01:30:07'),
(3, 'TRACKERTEER WEB DEVELOPMENT CORP.', 'pj', 'asd', '', '2025-04-30', '2025-04-30', 'asd', 'a', '1', 0, 0, 'a', 'a', 'a', 'a', '4.00', '2025-04-30 01:30:37'),
(4, 'BW MANUFACTURING CORP.', 'a', 'asd', '', '2025-05-08', '2025-05-08', 'asd', 'a', '1', 0, 0, 'a', 'a', 'a', 'a', '34.00', '2025-05-08 03:48:49');

-- --------------------------------------------------------

--
-- Table structure for table `service_request_items`
--

CREATE TABLE `service_request_items` (
  `id` int(11) NOT NULL,
  `request_id` int(11) DEFAULT NULL,
  `item_code` varchar(100) DEFAULT NULL,
  `description` text,
  `unit_price` decimal(12,2) DEFAULT NULL,
  `total_price` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `service_request_items`
--

INSERT INTO `service_request_items` (`id`, `request_id`, `item_code`, `description`, `unit_price`, `total_price`) VALUES
(1, 1, 'a', 'a', '4.00', '4.00'),
(2, 1, 'a', 'awsdsaddaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaasdad', '2.00', '2.00'),
(3, 2, 'a', 'a', '4.00', '4.00'),
(4, 3, 'a', 'a', '4.00', '4.00'),
(5, 4, 'a', '11', '34.00', '34.00');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `supplier_code` varchar(50) NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `address` text,
  `tax_id` varchar(100) DEFAULT NULL,
  `branch_code` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `payment_terms` varchar(255) DEFAULT NULL,
  `payment_method` varchar(100) DEFAULT NULL,
  `receipt_reminder` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `supplier_code`, `supplier_name`, `type`, `address`, `tax_id`, `branch_code`, `phone`, `email`, `contact_person`, `tags`, `payment_terms`, `payment_method`, `receipt_reminder`, `created_at`) VALUES
(1, 'SUP-68141A5A69229', 'a', 'Individual, NON VAT', 'Banaba, Dapdap', '12321', '1', '1231231', '123@gmail.com', 'asdadsa', 'electronic', 'Cash', '', '12days', '2025-05-02 01:05:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_code` (`item_code`);

--
-- Indexes for table `inventory_stock`
--
ALTER TABLE `inventory_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `item_file`
--
ALTER TABLE `item_file`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `material_request`
--
ALTER TABLE `material_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `material_request_items`
--
ALTER TABLE `material_request_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `mrr`
--
ALTER TABLE `mrr`
  ADD PRIMARY KEY (`id`),
  ADD KEY `po_id` (`po_id`);

--
-- Indexes for table `mrr_transactions`
--
ALTER TABLE `mrr_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mrr_id` (`mrr_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_request_items`
--
ALTER TABLE `service_request_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `supplier_code` (`supplier_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventory_items`
--
ALTER TABLE `inventory_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_stock`
--
ALTER TABLE `inventory_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_file`
--
ALTER TABLE `item_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `material_request`
--
ALTER TABLE `material_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `material_request_items`
--
ALTER TABLE `material_request_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `mrr`
--
ALTER TABLE `mrr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `mrr_transactions`
--
ALTER TABLE `mrr_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `service_request_items`
--
ALTER TABLE `service_request_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventory_stock`
--
ALTER TABLE `inventory_stock`
  ADD CONSTRAINT `inventory_stock_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `inventory_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `material_request_items`
--
ALTER TABLE `material_request_items`
  ADD CONSTRAINT `material_request_items_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `material_request` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mrr`
--
ALTER TABLE `mrr`
  ADD CONSTRAINT `mrr_ibfk_1` FOREIGN KEY (`po_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mrr_transactions`
--
ALTER TABLE `mrr_transactions`
  ADD CONSTRAINT `mrr_transactions_ibfk_1` FOREIGN KEY (`mrr_id`) REFERENCES `mrr` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mrr_transactions_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `purchase_order_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_request_items`
--
ALTER TABLE `service_request_items`
  ADD CONSTRAINT `service_request_items_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `service_requests` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
