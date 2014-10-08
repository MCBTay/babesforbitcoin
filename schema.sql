-- MySQL dump 10.13  Distrib 5.6.19, for debian-linux-gnu (x86_64)
--
-- Host: bfb-prod-db.crfteigz1fo2.us-east-1.rds.amazonaws.com    Database: babesforbitcoin_com_www
-- ------------------------------------------------------
-- Server version	5.6.17-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `assets`
--

DROP TABLE IF EXISTS `assets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assets` (
  `asset_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `asset_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `photoset_id` int(11) unsigned NOT NULL DEFAULT '0',
  `is_cover_photo` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `asset_cost` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `asset_title` varchar(20) NOT NULL DEFAULT '',
  `filename` varchar(250) NOT NULL DEFAULT '',
  `video` varchar(250) NOT NULL DEFAULT '',
  `asset_hd` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `approved` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `approved_by` int(11) unsigned NOT NULL DEFAULT '0',
  `approved_on` int(11) unsigned NOT NULL DEFAULT '0',
  `asset_created` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`asset_id`),
  KEY `asset_type` (`asset_type`),
  KEY `approved` (`approved`),
  KEY `approved_by` (`approved_by`),
  KEY `photoset_id` (`photoset_id`),
  KEY `user_id` (`user_id`),
  KEY `deleted` (`deleted`),
  KEY `default` (`default`)
) ENGINE=InnoDB AUTO_INCREMENT=1650 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `assets_tags`
--

DROP TABLE IF EXISTS `assets_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assets_tags` (
  `asset_tag_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) unsigned NOT NULL DEFAULT '0',
  `tag_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`asset_tag_id`),
  KEY `tag_id` (`tag_id`),
  KEY `asset_id` (`asset_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `assets_types`
--

DROP TABLE IF EXISTS `assets_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assets_types` (
  `asset_type_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `asset_type_title` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`asset_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `blocked_ips`
--

DROP TABLE IF EXISTS `blocked_ips`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blocked_ips` (
  `blocked_ip_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `blocked_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`blocked_ip_id`),
  KEY `blocked_ip` (`blocked_ip`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `carriers`
--

DROP TABLE IF EXISTS `carriers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carriers` (
  `carrier_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `carrier_name` varchar(50) NOT NULL DEFAULT '',
  `carrier_domain` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`carrier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `conversions`
--

DROP TABLE IF EXISTS `conversions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conversions` (
  `conversion_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cb_code` varchar(50) NOT NULL DEFAULT '',
  `btc_out` decimal(12,8) unsigned NOT NULL DEFAULT '0.00000000',
  `usd_in` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `site_fee` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `payout_date` int(11) unsigned NOT NULL DEFAULT '0',
  `created` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`conversion_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `message_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `user_id_from` int(11) unsigned NOT NULL DEFAULT '0',
  `user_id_to` int(11) unsigned NOT NULL DEFAULT '0',
  `read` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `html` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `message_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `message_created` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`message_id`),
  KEY `user_id_to` (`user_id_to`),
  KEY `user_id_from` (`user_id_from`),
  KEY `read` (`read`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=706 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `order_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `method` varchar(7) NOT NULL DEFAULT '',
  `amount` decimal(10,6) unsigned NOT NULL DEFAULT '0.000000',
  `fee` decimal(10,6) unsigned NOT NULL DEFAULT '0.000000',
  `total` decimal(10,6) unsigned NOT NULL DEFAULT '0.000000',
  `total_btc` decimal(10,6) unsigned NOT NULL DEFAULT '0.000000',
  `currency` varchar(3) NOT NULL DEFAULT '',
  `completed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` int(11) unsigned NOT NULL DEFAULT '0',
  `checkout_id` varchar(50) NOT NULL DEFAULT '',
  `clearing_date` int(11) unsigned NOT NULL DEFAULT '0',
  `error` varchar(250) NOT NULL DEFAULT '',
  `signature` varchar(50) NOT NULL DEFAULT '',
  `status` varchar(50) NOT NULL DEFAULT '',
  `transaction_id` int(11) unsigned NOT NULL DEFAULT '0',
  `cb_code` varchar(50) NOT NULL DEFAULT '',
  `epoch_json` text NOT NULL,
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `setting_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `setting_name` varchar(50) NOT NULL DEFAULT '',
  `setting_value` varchar(250) NOT NULL DEFAULT '',
  `setting_updated` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`setting_id`),
  UNIQUE KEY `setting_name` (`setting_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `tag_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `tag` (`tag`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `display_name` varchar(15) NOT NULL DEFAULT '',
  `admin_thumb` varchar(250) NOT NULL DEFAULT '',
  `email` varchar(250) NOT NULL DEFAULT '',
  `password` varchar(129) NOT NULL DEFAULT '',
  `reset_hash` varchar(64) NOT NULL DEFAULT '',
  `reset_expiration` int(11) unsigned NOT NULL DEFAULT '0',
  `accept_btc` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `prefer_btc` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `funds_btc` decimal(10,6) unsigned NOT NULL DEFAULT '0.000000',
  `funds_usd` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `text_number` varchar(10) NOT NULL DEFAULT '',
  `text_carrier` int(11) unsigned NOT NULL DEFAULT '0',
  `notify_email_messages` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `notify_email_photos` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `notify_email_videos` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `notify_text_messages` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `notify_text_photos` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `notify_text_videos` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user_hd` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `trusted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `featured` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `featured_sort` int(11) unsigned NOT NULL DEFAULT '0',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `lockout` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user_approved` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user_approved_by` int(11) unsigned NOT NULL DEFAULT '0',
  `user_approved_on` int(11) unsigned NOT NULL DEFAULT '0',
  `date_of_birth` varchar(10) NOT NULL DEFAULT '',
  `profile` text NOT NULL,
  `agree_terms` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` int(11) unsigned NOT NULL DEFAULT '0',
  `last_login` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  KEY `reset_hash` (`reset_hash`),
  KEY `user_type` (`user_type`),
  KEY `disabled` (`disabled`),
  KEY `lockout` (`lockout`),
  KEY `approved` (`user_approved`),
  KEY `featured` (`featured`)
) ENGINE=InnoDB AUTO_INCREMENT=263 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_log`
--

DROP TABLE IF EXISTS `users_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_log` (
  `user_log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `created` int(11) unsigned NOT NULL DEFAULT '0',
  `action` varchar(50) NOT NULL DEFAULT '',
  `ip_address` varchar(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_log_id`),
  KEY `action` (`action`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2201 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_purchases`
--

DROP TABLE IF EXISTS `users_purchases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_purchases` (
  `purchase_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `purchase_price` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `purchase_price_usd` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `purchase_price_btc` decimal(10,6) unsigned NOT NULL DEFAULT '0.000000',
  `model_usd` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `model_btc` decimal(10,6) unsigned NOT NULL DEFAULT '0.000000',
  `site_usd` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `site_btc_converted` decimal(10,6) unsigned NOT NULL DEFAULT '0.000000',
  `cb_code` varchar(50) NOT NULL DEFAULT '',
  `payout_date` int(11) unsigned NOT NULL DEFAULT '0',
  `purchase_created` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`purchase_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_tags`
--

DROP TABLE IF EXISTS `users_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_tags` (
  `user_tag_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `tag_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_tag_id`),
  KEY `tag_id` (`tag_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_types`
--

DROP TABLE IF EXISTS `users_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_types` (
  `user_type_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_type_title` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_withdrawals`
--

DROP TABLE IF EXISTS `users_withdrawals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_withdrawals` (
  `withdrawal_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `currency` varchar(3) NOT NULL DEFAULT '',
  `withdrawal_amount` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `funds_usd_remaining` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `funds_btc_remaining` decimal(10,6) unsigned NOT NULL DEFAULT '0.000000',
  `site_fee` decimal(6,2) unsigned NOT NULL DEFAULT '0.00',
  `transaction_id` varchar(250) NOT NULL DEFAULT '',
  `transaction_error` varchar(250) NOT NULL DEFAULT '',
  `refunded` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `withdrawal_created` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`withdrawal_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-10-07 20:09:55
