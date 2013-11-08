-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 19, 2012 at 09:25 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `improvhealth`
--

-- --------------------------------------------------------

--
-- Table structure for table `core_settings`
--

CREATE TABLE IF NOT EXISTS `core_settings` (
  `slug` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `default` text COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`slug`),
  UNIQUE KEY `unique - slug` (`slug`),
  KEY `index - slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Stores settings for the multi-site interface';

--
-- Dumping data for table `core_settings`
--

INSERT INTO `core_settings` (`slug`, `default`, `value`) VALUES
('date_format', 'g:ia -- m/d/y', 'g:ia -- m/d/y'),
('lang_direction', 'ltr', 'ltr'),
('status_message', 'This site has been disabled by a super-administrator.', 'This site has been disabled by a super-administrator.');

-- --------------------------------------------------------

--
-- Table structure for table `core_sites`
--

CREATE TABLE IF NOT EXISTS `core_sites` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ref` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `domain` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_on` int(11) NOT NULL DEFAULT '0',
  `updated_on` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `Unique ref` (`ref`),
  UNIQUE KEY `Unique domain` (`domain`),
  KEY `ref` (`ref`),
  KEY `domain` (`domain`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `core_sites`
--

INSERT INTO `core_sites` (`id`, `name`, `ref`, `domain`, `active`, `created_on`, `updated_on`) VALUES
(1, 'Default Site', 'default', '127.0.0.1', 1, 1326243426, 0);

-- --------------------------------------------------------

--
-- Table structure for table `core_users`
--

CREATE TABLE IF NOT EXISTS `core_users` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `salt` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `group_id` int(11) DEFAULT NULL,
  `ip_address` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` int(1) DEFAULT NULL,
  `activation_code` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_on` int(11) NOT NULL,
  `last_login` int(11) NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `forgotten_password_code` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_code` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Super User Information' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `core_users`
--

INSERT INTO `core_users` (`id`, `email`, `password`, `salt`, `group_id`, `ip_address`, `active`, `activation_code`, `created_on`, `last_login`, `username`, `forgotten_password_code`, `remember_code`) VALUES
(1, 'ronaldrhey@gmail.com', '31c6b769c8a2d6d037d303c4ce9f7c15d5d3ab05', 'c0dd6', 1, '', 1, '', 1326243426, 1326243426, 'admin', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `default_assessment`
--

CREATE TABLE IF NOT EXISTS `default_assessment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `disease_id` int(11) NOT NULL,
  `consultation_id` int(11) NOT NULL,
  `date_added` int(11) NOT NULL,
  `gender` enum('male','female') NOT NULL DEFAULT 'male',
  `client_age` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `default_assessment`
--

INSERT INTO `default_assessment` (`id`, `client_id`, `disease_id`, `consultation_id`, `date_added`, `gender`, `client_age`) VALUES
(2, 5, 24, 1, 1325458800, 'female', 25),
(3, 8, 25, 2, 1329260400, 'female', 31),
(4, 9, 27, 3, 1337292000, 'female', 23),
(5, 9, 28, 4, 1337119200, 'female', 23),
(6, 10, 2, 5, 1337292000, 'male', 0),
(7, 11, 26, 6, 1337119200, 'male', 11),
(8, 8, 1, 1, 1336341600, 'female', 31);

-- --------------------------------------------------------

--
-- Table structure for table `default_bhs`
--

CREATE TABLE IF NOT EXISTS `default_bhs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  `municipality_id` int(10) NOT NULL,
  `province_id` int(10) NOT NULL,
  `region_id` int(10) NOT NULL,
  `rhu` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `default_bhs`
--


-- --------------------------------------------------------

--
-- Table structure for table `default_blog`
--

CREATE TABLE IF NOT EXISTS `default_blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `category_id` int(11) NOT NULL,
  `attachment` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `intro` text COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `parsed` text COLLATE utf8_unicode_ci NOT NULL,
  `keywords` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `author_id` int(11) NOT NULL DEFAULT '0',
  `created_on` int(11) NOT NULL,
  `updated_on` int(11) NOT NULL DEFAULT '0',
  `comments_enabled` int(1) NOT NULL DEFAULT '1',
  `status` enum('draft','live') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `type` set('html','markdown','wysiwyg-advanced','wysiwyg-simple') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`),
  KEY `category_id - normal` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Blog posts.' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `default_blog`
--


-- --------------------------------------------------------

--
-- Table structure for table `default_blog_categories`
--

CREATE TABLE IF NOT EXISTS `default_blog_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `title` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug - unique` (`slug`),
  UNIQUE KEY `title - unique` (`title`),
  KEY `slug - normal` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Blog Categories.' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `default_blog_categories`
--

INSERT INTO `default_blog_categories` (`id`, `slug`, `title`) VALUES
(1, 'my-cat', 'my cat'),
(2, 'okoko', 'okoko');

-- --------------------------------------------------------

--
-- Table structure for table `default_children_under_one`
--

CREATE TABLE IF NOT EXISTS `default_children_under_one` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `client_id` int(10) NOT NULL,
  `mother_name` varchar(200) NOT NULL,
  `nb_referral_date` int(11) DEFAULT NULL,
  `nb_done_date` int(11) DEFAULT NULL,
  `cp_date_assess` int(11) DEFAULT NULL,
  `cp_tt_status` varchar(20) DEFAULT NULL,
  `ms_a_age_months` tinyint(4) DEFAULT NULL,
  `ms_a_date_given` int(11) DEFAULT NULL,
  `ms_iron_birth_weight` varchar(50) DEFAULT NULL,
  `ms_iron_date_started` int(11) DEFAULT NULL,
  `ms_iron_date_completed` int(11) DEFAULT NULL,
  `im_bcg` int(11) DEFAULT NULL,
  `im_dpt1` int(11) DEFAULT NULL,
  `im_dpt2` int(11) DEFAULT NULL,
  `im_dpt3` int(11) DEFAULT NULL,
  `im_polio1` int(11) DEFAULT NULL,
  `im_polio2` int(11) DEFAULT NULL,
  `im_polio3` int(11) DEFAULT NULL,
  `im_hepa_b1_with_in` int(11) DEFAULT NULL,
  `im_hepa_b1_more_than` int(11) DEFAULT NULL,
  `im_hepa_b2` int(11) DEFAULT NULL,
  `im_hepa_b3` int(11) DEFAULT NULL,
  `im_anti_measles` int(11) DEFAULT NULL,
  `im_fully` int(11) DEFAULT NULL,
  `bf_1` enum('1','0') DEFAULT '0',
  `bf_2` enum('1','0') DEFAULT '0',
  `bf_3` enum('1','0') DEFAULT '0',
  `bf_4` enum('1','0') DEFAULT '0',
  `bf_5` enum('1','0') DEFAULT '0',
  `bf_6` int(11) DEFAULT NULL,
  `remarks` varchar(200) DEFAULT NULL,
  `date_added` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `default_children_under_one`
--


-- --------------------------------------------------------

--
-- Table structure for table `default_ci_sessions`
--

CREATE TABLE IF NOT EXISTS `default_ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `user_agent` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `default_ci_sessions`
--

INSERT INTO `default_ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('dad45cebfb03ec63eeeb63ad948a2a57', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.75 Safari/535.7', 1326239838, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('f1bf70f585587ec09248397041941aa4', '192.168.1.7', 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_3_1 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2', 1326240297, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('811f513573bb3f8046aba5169a35bb19', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1326312322, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('e5dbbc0016daca23732d652479bfc6f1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1326337382, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('1a6f0547019b35ef6e4321ccddce3a3a', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1326602232, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('141ed9daa4c3813a12bf78b763238497', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.75 Safari/535.7', 1326608990, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('baf94f7fc72bbbcd8fc203bec8af6666', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1326869175, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('d2be7d602ce70c89d02105eaf2e8a695', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.75 Safari/535.7', 1326868568, ''),
('081eb4aad40465285d3913c0d1072266', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1327043196, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('b22340203a18d60d3e013266ab5b5871', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.75 Safari/535.7', 1327097501, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('406b8986981e5f5c56c31c55ba4cb381', '192.168.1.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.75 Safari/535.7', 1327096651, ''),
('d5c6796f33ae99c8ffe40bc500e6dec7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1327182051, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('74c9ac6a6f05c1c6abff35b8d475991a', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1327257384, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('e0770d26b46b6d85022065600f3b58e1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1327257859, 'a:6:{s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('e6d4a1f1532683031b893cece74a388d', '10.0.0.4', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.75 Safari/535.7', 1327357494, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('8cf6eb9a169bc3b821ba3feb0d1a3d57', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1327424143, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('e5488a40b1b9902e922b22efd19d46b8', '10.0.0.10', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.75 Safari/535.7', 1327514798, ''),
('6fbbabddee053f78d8bea8ff17171c7a', '10.0.0.10', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.75 Safari/535.7', 1327514800, ''),
('1ad73481221daf96ad3abf4002134be1', '10.0.0.11', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.75 Safari/535.7', 1327550962, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('7e223599874cbaf35d170c4b83a7bc80', '10.0.0.9', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1327599912, ''),
('d19ed4aff1988bf3e6cd4bff0ef92ed8', '10.0.0.9', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1327688477, ''),
('70bfb6f2612ca10d2c81cd98edf072d0', '10.0.0.9', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1327689169, ''),
('83705b25eef54d1c9ca2e293416840e7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1327813950, 'a:8:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";s:17:"flash:old:success";s:42:"2 region(s) out of 2 successfully deleted.";}'),
('9848a196157e41786916ad995b8a01c4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1327814709, ''),
('2e1eb4c24f6fcd4d328e90253e79ad24', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1327814717, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('e91e93cf8172b9447f8cb646c53e8c70', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1327867184, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('0255d6dd2470c29441474f723c06201d', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1327910614, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('5ec737ef7a527ee1d03bf421f3c76fd5', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1327914293, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('a6f7048d7d1ecb37bdbba0461f5251d8', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1327914600, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('6bbdb32b2df8786fd9405371ee184264', '10.0.0.9', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1327961804, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('078518bc8fc4cb13a8d2af353d200f5a', '10.0.0.8', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1327984941, ''),
('91364a963869adc690a038e53697a89b', '10.0.0.8', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1327987112, ''),
('23c55c9c410cb9411651d6aefc9c44d3', '10.0.0.10', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328035472, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('c2b449455bec7e28cabb287b4bfdd671', '0.0.0.0', 'Microsoft-WebDAV-MiniRedir/6.1.7600', 1328050568, ''),
('a4006b1b11753097f4109e7ba9ac6551', '0.0.0.0', 'Microsoft-WebDAV-MiniRedir/6.1.7600', 1328050571, ''),
('f4d2a6673732fb1bd475210e54dcb705', '0.0.0.0', 'Microsoft-WebDAV-MiniRedir/6.1.7600', 1328050572, ''),
('d3f0badffa8b40ddcf92ec012b406781', '10.0.0.9', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328069247, 'a:6:{s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('4d785198ca8983a298614ea786476799', '10.0.0.3', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328133262, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('9bae094a3ddf9edc78f97591e0e77f43', '10.0.0.5', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328225300, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('fe893bfaf4bfdd2bd345ebe01afa933a', '10.0.0.6', 'Mozilla/5.0 (Windows NT 6.1; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1328282761, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('1fac0f9cecf5aca895c6d095fc77c5ab', '10.0.0.6', 'Microsoft-WebDAV-MiniRedir/6.1.7600', 1328206601, ''),
('a59a01d11f6830ba677e3046c83311c0', '10.0.0.6', 'Microsoft-WebDAV-MiniRedir/6.1.7600', 1328206603, ''),
('8b5602733a7b67e1dfbe4a7ba23ad49b', '10.0.0.6', 'Microsoft-WebDAV-MiniRedir/6.1.7600', 1328206604, ''),
('72b80ccc2d86a13a22bf3631522957c9', '10.0.0.5', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328245275, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('5bccdfbfd308c0f8234b98ae0f59073d', '10.0.0.4', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328280348, ''),
('4538c950bd35c64d9da591e9a7bd9282', '10.0.0.4', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328280972, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('7639e03d78b7be8270de7af61fd05a5d', '10.0.0.6', 'Mozilla/5.0 (Windows NT 6.1; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1328282764, ''),
('5cfade18876871214a770b3a88671722', '10.0.0.6', 'Mozilla/5.0 (Windows NT 6.1; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1328282765, ''),
('42774d92c4a66bc09240a9ca60cf46ea', '10.0.0.6', 'Mozilla/5.0 (Windows NT 6.1; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1328282766, ''),
('47547eaddc3e7a2f2f8cbef4d530d552', '10.0.0.6', 'Mozilla/5.0 (Windows NT 6.1; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1328282766, ''),
('91c41142cd3850933c6f2fb649fdf290', '10.0.0.10', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328303832, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('e01d75b6e92e78cdba589f0cd48fcd26', '10.0.0.10', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328318945, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('61b825eb1c944c4ea1199ca41c272b8a', '10.0.0.4', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328327564, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('d6855732c0faed13ffeab623a05f7d60', '10.0.0.10', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328377473, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('2cfa9bbe781f11dd9887d62e7f53d917', '10.0.0.13', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328424692, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('c6ed98cdd81c859d34bc6aaf1399934a', '10.0.0.13', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1328423806, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('208885b6b7d25a0cc3371f3975f33959', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1328438082, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('a6b0a564f3d7ac794e3622df1e26ff74', '10.0.0.10', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328455238, ''),
('5bf8f457fcff340c7ddb889187f49d85', '10.0.0.10', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328455238, ''),
('9b3d494bf08bc9c654053ff8b3a53885', '10.0.0.7', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328504769, ''),
('65692c85e75aad46ec2ddbc04d04021b', '10.0.0.7', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328504776, ''),
('ec7b7af028eb4d988c01ca330d0c68b2', '10.0.0.2', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328579977, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('f851732ac366bc812f91a4577d3c06e7', '10.0.0.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1328558343, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('9bf040ed118dc0460add36f61432827c', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1328558693, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('8b9cd078c708fb90d9fd8d34294ed6e8', '10.0.0.5', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328587228, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('7b66d1af548240206af041296e864f9c', '10.0.0.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1328597579, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('956041dafd1ee99abde6f17c0b0c9e28', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1328611115, 'a:8:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";s:14:"current_action";s:2:"fp";}'),
('971f8969bea551fd348484b8c8dfb3da', '10.0.0.4', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1328623772, ''),
('6beca7ef03f5d02d15bcaa065b43f888', '10.0.0.4', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1328623775, ''),
('867ba73eeb5293dc4b1a8d1b9146175a', '10.0.0.4', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1328623776, ''),
('090de7c196325b1a4c864529e776d26a', '10.0.0.4', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1328623777, ''),
('4dbdaf9e46f5dc5332eb5a265ccc841a', '10.0.0.4', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1328623819, ''),
('c5b954d259a9000672dc272b3f668945', '10.0.0.4', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328656828, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('3df27a1b252878038ea16ea902f97a21', '10.0.0.4', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328668687, ''),
('e3e6a26ef8d3d79fdf70501dc6ecc946', '10.0.0.4', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328675239, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('7d7252764b8120a6f982e024270b6344', '10.0.0.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1328685932, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('591bdee089b665191a3e042963ad9b82', '10.0.0.4', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328687766, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('94e6041c22fcce6940a73c3b6def44b9', '10.0.0.4', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1328685040, ''),
('1673482047e08623a04a04c092c829a2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1328723457, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('db7564af4f77762734547093ecf33536', '10.0.0.2', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328720646, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('48735bdaf6e3651dfa4221501edd8953', '10.0.0.5', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328722732, ''),
('e3588d0c31d64629842eeab283d8136c', '10.0.0.7', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328735306, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('2174dea91122fa188dd2d1e9783bd4e9', '10.0.0.3', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328760481, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('0072bb179b21d5b50bef957dcc8c7870', '10.0.0.3', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1328754283, ''),
('4bb2757ff224882bb27bf3a0e83883ac', '10.0.0.3', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1328760766, 'a:6:{s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('78a86985334f1e5be9fa347872529dfe', '10.0.0.10', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328777109, ''),
('04d7ed96c8c2b6ce0790729c30d372b7', '10.0.0.10', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328778878, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('4515fd61e2045847c3c19c3d0398f9b9', '10.0.0.6', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328818623, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('fa6d62e7e8cab2588bfe43cefce6cac6', '10.0.0.6', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328820826, ''),
('e45dea06d542f1f09ed1df09cca59cad', '10.0.0.6', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1328834677, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('c84aeaff60793dd1cfae7e79cbb371fa', '10.0.0.8', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.46 Safari/535.11', 1328841858, ''),
('d81a37f18b61b7ec3e360a8263eda1f1', '10.0.0.8', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.46 Safari/535.11', 1328841858, ''),
('6a72544d3b0fe44fd5911831976e920f', '10.0.0.9', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.46 Safari/535.11', 1328848269, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('90af2c840ac2b7864c3749036f5db077', '10.0.0.9', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.46 Safari/535.11', 1328843040, ''),
('8b4228a14bbde10a6c106ba65ec64737', '10.0.0.9', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1328844241, ''),
('8d5fe182174706ae707a2f8694c7ce47', '10.0.0.11', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.46 Safari/535.11', 1328861815, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('52a955baaeead324ef6f3c97d2ff4d88', '10.0.0.3', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.46 Safari/535.11', 1328908209, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('944038eb5dcaa15c09522edbd24dfb5d', '10.0.0.3', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1328906017, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('d1749a9cd1e317a30a9f3354b6de76c9', '10.0.0.3', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1328906021, ''),
('9d9f54c79693010e4b50a5196b7bf30d', '10.0.0.3', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.46 Safari/535.11', 1328912470, ''),
('36b78aa29ff24363b13ca4dfac66a7cd', '10.0.0.3', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.46 Safari/535.11', 1328912470, ''),
('37d3c7934b6eb34c69d355fbc8598c53', '10.0.0.3', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.46 Safari/535.11', 1328912471, ''),
('907562c15b84e9049f0a511913aff1c5', '10.0.0.6', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1328917714, ''),
('cbae1240729a46adceb9506da0db5870', '10.0.0.6', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1328947760, 'a:6:{s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('a0f886003bb101493c914b85bf1fb2f2', '10.0.0.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1328942308, ''),
('1a5ac7f1b213b716e00bbc073a97c18a', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1328957930, 'a:8:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";s:17:"flash:new:success";s:29:"Children successfully updated";}'),
('ee5ac4a612b3d7b1c2f038791b7f43c3', '10.0.0.5', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.46 Safari/535.11', 1329025668, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('4d5797ab16d7599b9919db5138031a88', '10.0.0.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.46 Safari/535.11', 1329035437, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('5b64a0126ca2b5d41b4407bf498ce911', '10.0.0.11', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1329026247, ''),
('4085418980f49594c7d8342ea058e802', '10.0.0.11', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1329026247, ''),
('55a2b3c1df5fb48ec0474406e26f037f', '10.0.0.11', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1329026248, ''),
('3aa08e7d67b94bf7a47c18d2d2052712', '10.0.0.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1329034571, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('1d2ba276b530f6e9073fe5e7af4dcf18', '10.0.0.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1329034574, ''),
('cd08fc7aa53581c42690060b6ba23bc9', '10.0.0.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1329034915, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('1ad24fe734042edb9c1b64412f8ed200', '10.0.0.5', 'Mozilla/5.0 (Windows NT 6.1; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1329107403, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('85e2d6f61fc32ac2356ed4634d4c8da6', '10.0.0.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1329100127, ''),
('27006a6e87cffd19e9d2a8c874f2051b', '10.0.0.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.46 Safari/535.11', 1329151250, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('54102bd129d481f57e09d5d894561b9c', '10.0.0.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.46 Safari/535.11', 1329151765, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('7efc0cce1fdaab5406a1e2e1432e625f', '10.0.0.8', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1329179951, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('070213557a4c4388fdb20858a0178988', '10.0.0.5', 'Mozilla/5.0 (Windows NT 6.1; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1329176799, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('aa3bcf6e5029869dd9dae7e2c1bba6d7', '10.0.0.7', 'Mozilla/5.0 (Windows NT 6.1; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1329186692, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('054792c0cbf42197957a28f65402650b', '10.0.0.10', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1329254792, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('f523da1297973dfd90843466be539f83', '10.0.0.10', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1329297069, 'a:6:{s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('3453d3bdaede52f9d34ae7faafdc9223', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.46 Safari/535.11', 1329432008, ''),
('2890d38ed06c913b9632742cb9e2cfaa', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1329432864, ''),
('35d743aa0e6f18d551fbbdd49f2fbae8', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1329500340, 'a:6:{s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('fddb4a3e9b736ad4e449a156489f6a31', '10.0.0.8', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1329525106, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('005b4045a6dd50eaae0db77cda100d18', '10.0.0.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11', 1329600427, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:6:"reagan";s:5:"email";s:19:"msgeonzon@gmail.com";s:2:"id";s:1:"2";s:7:"user_id";s:1:"2";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";}'),
('a0c396f51f6f6bc0d4db9c33dd6d7434', '10.0.0.7', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7', 1329518373, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('5d1f2e7fce880f2287d929cac5719f58', '10.0.0.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1329552866, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('f9e8d3d4edb402a5aec68a62f7ced8ee', '10.0.0.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1329642506, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:7:"chelsea";s:5:"email";s:17:"chelsea@gmail.com";s:2:"id";s:1:"3";s:7:"user_id";s:1:"3";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";}'),
('a0254bb7a78f24021651bb589144ca6c', '10.0.0.9', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1329604258, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('578eb49d0c50c3fd47e3ed5d43e51a4d', '10.0.0.12', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1329617721, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('5974aa71d8203c08ba1abc63210d18ba', '10.0.0.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11', 1329634878, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('b1c1fc75d5ca261e95875557253fcdc8', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11', 1329663763, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:7:"chelsea";s:5:"email";s:17:"chelsea@gmail.com";s:2:"id";s:1:"3";s:7:"user_id";s:1:"3";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";}'),
('6dfaceb6a76e0c54ed2f13bcc5c30477', '10.0.0.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11', 1329672217, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:7:"chelsea";s:5:"email";s:17:"chelsea@gmail.com";s:2:"id";s:1:"3";s:7:"user_id";s:1:"3";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";}'),
('ad59e2fb5ac34574fa69b758733794bd', '10.0.0.3', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11', 1329697116, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:7:"chelsea";s:5:"email";s:17:"chelsea@gmail.com";s:2:"id";s:1:"3";s:7:"user_id";s:1:"3";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";}'),
('38af9f1fd2e9282c0c28defde0b6dbb3', '10.0.0.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1329673640, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:7:"chelsea";s:5:"email";s:17:"chelsea@gmail.com";s:2:"id";s:1:"3";s:7:"user_id";s:1:"3";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";}'),
('1da6be87769afb5af4496375e57d4ebe', '10.0.0.9', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1329684376, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('42bd0586c59dc133e153a20ea5c94050', '10.0.0.9', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1329684376, ''),
('55bc984f712b3e92cc4e630f19bf7b46', '10.0.0.9', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1329684377, ''),
('12c2a988c8a5872da722bdfbab582f21', '10.0.0.10', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1329684949, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('e285efe8415b048c33cbd551159ce631', '10.0.0.10', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11', 1329685030, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:7:"chelsea";s:5:"email";s:17:"chelsea@gmail.com";s:2:"id";s:1:"3";s:7:"user_id";s:1:"3";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";}'),
('5f489ce8fefdf884a8003b79f829516c', '10.0.0.3', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11', 1329712412, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:7:"chelsea";s:5:"email";s:17:"chelsea@gmail.com";s:2:"id";s:1:"3";s:7:"user_id";s:1:"3";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";}'),
('dd4cf7e5d7b66c45c1574bc5505c3ab2', '10.0.0.8', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1329695440, ''),
('115e14600e3da09c7ac16698f5e4912b', '10.0.0.8', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1329695441, ''),
('17e97d80d4be39f4ba28bcafbdd8dec1', '10.0.0.8', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1329695839, ''),
('56d0338fff7395dc8e45bd8a5aab6279', '10.0.0.8', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1329695840, ''),
('8ba75c30a6cbc09f4a76fd330c033554', '10.0.0.3', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11', 1329712091, 'a:8:{s:9:"user_data";s:0:"";s:8:"username";s:7:"chelsea";s:5:"email";s:17:"chelsea@gmail.com";s:2:"id";s:1:"3";s:7:"user_id";s:1:"3";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:17:"flash:new:success";s:42:"1 childrens out of 1 successfully deleted.";}'),
('ca8c31101fe80a85d6835d1d1131004e', '10.0.0.6', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11', 1329720901, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:7:"chelsea";s:5:"email";s:17:"chelsea@gmail.com";s:2:"id";s:1:"3";s:7:"user_id";s:1:"3";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";}'),
('8a27c6501bade1ff7e7d7404b7f5df16', '10.0.0.9', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1329714390, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:7:"chelsea";s:5:"email";s:17:"chelsea@gmail.com";s:2:"id";s:1:"3";s:7:"user_id";s:1:"3";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";}'),
('bf27f1a64689d180c08088e9f6d1c6b0', '10.0.0.11', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1329700227, ''),
('a59f1e2c51a6a2ca3189030fda5d4247', '10.0.0.11', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1329720189, 'a:6:{s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('e832f56c46bff571fe607df48edcd56a', '10.0.0.11', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1329702166, ''),
('b3c9ebf99ff36d806ec7823563bb7e20', '10.0.0.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1330335032, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('b597cb7ae9402abee786d7483b5ca85b', '10.0.0.2', 'Mozilla/5.0 (Windows NT 6.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1329720846, ''),
('833da7a7de4aff349551cc1e631de9cb', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1329723905, ''),
('f3377b78bcede96b0d3200a16ca25b0b', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11', 1329726495, ''),
('0eeae6c5a17d4dc68a8d0f571ba4470d', '10.0.0.3', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11', 1329721049, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:7:"chelsea";s:5:"email";s:17:"chelsea@gmail.com";s:2:"id";s:1:"3";s:7:"user_id";s:1:"3";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";}'),
('1e02be2b2c12165b8b93866dbb970d34', '10.0.0.11', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1329720190, ''),
('350d6b2e9917418813c23858f10df93e', '10.0.0.11', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1329720838, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('f39076fda0cdb86ce5043b0982fb288c', '10.0.0.7', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:2.0) Gecko/20100101 Firefox/4.0', 1329727251, ''),
('af76ded3c855a546b96eda41eb1b4f1e', '10.0.0.4', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1329779972, 'a:2:{s:9:"user_data";s:0:"";s:11:"redirect_to";s:10:"users/edit";}'),
('483c79d76f493a0cb72190e34a954a8e', '10.0.0.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1330340971, ''),
('9e2ac3760a22d5ada406ed3c93407ac3', '10.0.0.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11', 1330410271, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";}'),
('0bb93393f99f0f975bef138f0246bcbc', '10.0.0.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1330340976, ''),
('579d1f26e03ba5c25a59957f92337830', '10.0.0.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1330341640, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";}'),
('6f8320aff44c28d380f15d00c47ee074', '10.0.0.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1330419121, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";}'),
('429d96d5b27c9a5a9a0e7afd7000750e', '10.0.0.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1331184656, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";}'),
('ba67e5ef581dbe93494d20c22f5f732c', '192.168.1.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1331932029, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('667d9c84ba945297a34296f98a11e6e2', '192.168.1.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1331934407, 'a:6:{s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";}'),
('3389319d791b1f56bbdf22f171047ac8', '192.168.1.43', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1331933158, ''),
('e3bffc0ed573ac4ebbc092496568be7d', '192.168.1.46', 'Mozilla/5.0 (Windows NT 6.1; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1331933181, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('3dc2778c3364cebf87101590b203a724', '192.168.1.4', 'Mozilla/5.0 (Windows NT 6.1; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1331974095, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('3721c78b86bc41d9342d18fe0dc8c0a5', '192.168.1.44', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR ', 1331948109, ''),
('cb148dceb285c2873e9841987c7c219a', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332014945, 'a:9:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:18:"selected_client_id";s:0:"";s:3:"set";s:7:"default";}'),
('fba05a96f258a04891cf915196d4fc44', '192.168.1.27', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1331963365, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('9d75e40692a5932bac816c31dd372f9a', '192.168.1.27', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1331965379, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('cc4225ac2123e1a8fd5ec0de371cc323', '192.168.1.44', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332010953, 'a:8:{s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:18:"selected_client_id";s:0:"";s:3:"set";s:7:"default";}'),
('0b45d634b2d82f9a7e80be82113d4e8b', '192.168.1.27', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1331965380, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('df58a5f0f9bc9c50a2adfa42fbf7345c', '0.0.0.0', 'Microsoft-WebDAV-MiniRedir/6.1.7600', 1331995268, ''),
('60411aabddb0f0561797cf3fba251075', '0.0.0.0', 'Microsoft-WebDAV-MiniRedir/6.1.7600', 1331995271, ''),
('ab6c701da404ec878941d9222b7c3764', '0.0.0.0', 'Microsoft-WebDAV-MiniRedir/6.1.7600', 1331995273, ''),
('d7c05897dd3ca059a60ab350838c5404', '0.0.0.0', 'Microsoft-WebDAV-MiniRedir/6.1.7600', 1331995274, ''),
('049deda6b84a2815e4ed21e1a00402d7', '0.0.0.0', 'Microsoft-WebDAV-MiniRedir/6.1.7600', 1331995274, ''),
('c9d119a26f97d95923313cc9a496091a', '0.0.0.0', 'Microsoft-WebDAV-MiniRedir/6.1.7600', 1331995274, ''),
('db9efc071f5d94a80b878ecd29d9807e', '192.168.1.65', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332018568, 'a:9:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:18:"selected_client_id";s:0:"";s:3:"set";s:7:"default";}'),
('63d43445d5b181c536f9e2de5af8292c', '192.168.1.53', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332008702, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('93361639b2c6a7f03ee4bf7f923bffed', '192.168.1.44', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR ', 1332006982, ''),
('15d004df73d39d74e4b63b64f8e3615d', '192.168.1.62', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332058589, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('3f29ea3b5676753cec2d7cb8ddda26a8', '192.168.1.62', 'Mozilla/5.0 (Windows NT 6.1; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332008084, ''),
('454381c04eec60a5694925f0a1770408', '192.168.1.62', 'Mozilla/5.0 (Windows NT 6.1; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332008777, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}');
INSERT INTO `default_ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('a5b520c2c2dae46251908d567e1055ba', '192.168.1.53', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332029107, 'a:10:{s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:9:"on_search";s:0:"";s:18:"selected_client_id";s:0:"";s:3:"set";s:7:"default";s:14:"search_keyword";s:0:"";}'),
('9b2edf200b13bc9b4de5102a87ca93a6', '192.168.1.76', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332031098, ''),
('7e0fc0b2f94a83b28c74466ee4d74672', '192.168.1.62', 'Mozilla/5.0 (Windows NT 6.1; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332008778, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('e344657a446ecc63826c017eb7ebf359', '192.168.1.62', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332025504, 'a:9:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";s:18:"selected_client_id";s:0:"";s:3:"set";s:7:"default";}'),
('426de4706be7dd33fc3591a7681395e4', '192.168.1.44', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332010955, ''),
('398946ba3c6bdff8f81aea48e70fd9f0', '192.168.1.44', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332012711, ''),
('fc491d8dbe594e6e9a7cc5a47e353da7', '192.168.1.65', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332015000, ''),
('57220a4df81c19c55b8986fde271faa4', '192.168.1.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332032356, 'a:11:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:9:"on_search";s:0:"";s:14:"search_keyword";s:0:"";s:18:"selected_client_id";s:0:"";s:3:"set";s:7:"default";}'),
('56a84c0bf53ba8fbd6c362c5dd151d8d', '192.168.1.76', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332026134, ''),
('cd14c83cdd2a89eb77ae9c139fed3750', '192.168.1.76', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332026134, ''),
('aa973b68a3e0407acb43f6a1f43fd088', '192.168.1.76', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332027538, 'a:6:{s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";}'),
('53963bb5edf6c3f3406c87b2b0ce8ed0', '0.0.0.0', 'Microsoft-WebDAV-MiniRedir/6.1.7600', 1332031105, ''),
('58ae67fe8b7f05dddba4f756e4781439', '0.0.0.0', 'Microsoft-WebDAV-MiniRedir/6.1.7600', 1332031106, ''),
('3ab89d01915563ca8f0ec1fa3461d284', '0.0.0.0', 'Microsoft-WebDAV-MiniRedir/6.1.7600', 1332031107, ''),
('208b93782576ab93850c0dfbacb85c7b', '0.0.0.0', 'Microsoft-WebDAV-MiniRedir/6.1.7600', 1332031107, ''),
('1b0cf3a0362f27957b44cb087a01ba39', '192.168.1.80', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332045587, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";}'),
('c2d333ecebbe37ff9da928daef1c8521', '192.168.1.14', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332055079, ''),
('c5b35ed6f4e61d20d7c56cdcd9e03666', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332044245, 'a:11:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:9:"on_search";s:0:"";s:14:"search_keyword";s:0:"";s:18:"selected_client_id";s:0:"";s:3:"set";s:7:"default";}'),
('6715f9359f3480b5b71d3b1483179ff1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332055684, ''),
('0ed110983f188f870b9a487ed6229831', '192.168.1.62', 'Mozilla/5.0 (Windows NT 6.1; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332060828, 'a:11:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:18:"selected_client_id";s:0:"";s:3:"set";s:7:"default";s:9:"on_search";s:0:"";s:14:"search_keyword";s:0:"";}'),
('88f4fe78fa5d59525fdea4a933fb12d6', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332061015, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('90b2a7a0c547f3130d20ce27e4e01b20', '192.168.1.80', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332045589, ''),
('1ddb20f1b8fa41d49d184c2b0d6bc30c', '192.168.1.80', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332045590, ''),
('5cd417af9d325048efc82bbf7fb5bdde', '192.168.1.80', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332045590, ''),
('16352e229dbeb6ee7dc917f3165a902e', '192.168.1.80', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332045591, ''),
('fbfb9c0458d9acf679f302d57d8d88cb', '192.168.1.80', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332045591, ''),
('ed465910e16aca1177fa580ccd2211f3', '192.168.1.80', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332051403, ''),
('116d8f5eb0ebf0d7d170980081016e31', '192.168.1.80', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332051407, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";}'),
('3b42ba27ba29cb87eddc7170543d93aa', '192.168.1.62', 'Mozilla/5.0 (Windows NT 6.1; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332073468, 'a:10:{s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:18:"selected_client_id";s:0:"";s:3:"set";s:7:"default";s:9:"on_search";s:0:"";s:14:"search_keyword";s:0:"";}'),
('b72df32eb21fda47ddf085a3e3f95c13', '192.168.1.4', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332080312, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";}'),
('c8306a1f4e946d4ca01f5b8e5e9f7a60', '192.168.1.4', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:11.0) Gecko/20100101 Firefox/11.0', 1332080515, ''),
('03a596c4ced217e899f68049b59f60e4', '192.168.1.4', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:11.0) Gecko/20100101 Firefox/11.0', 1332080516, ''),
('3a3af9afd3250bd114795c540965bf81', '192.168.1.4', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:11.0) Gecko/20100101 Firefox/11.0', 1332080516, ''),
('1485420e7e0ab852fdc8d6205ea917f2', '192.168.1.4', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:11.0) Gecko/20100101 Firefox/11.0', 1332080517, ''),
('d517c34c2680d97511c86215bc51e6a9', '192.168.1.4', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:11.0) Gecko/20100101 Firefox/11.0', 1332097458, 'a:11:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:18:"selected_client_id";s:0:"";s:3:"set";s:7:"default";s:9:"on_search";s:0:"";s:14:"search_keyword";s:0:"";}'),
('99413d66a872980890adbeed4f9ee2e5', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332105743, 'a:9:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";s:9:"on_search";s:0:"";s:14:"search_keyword";s:0:"";}'),
('5e4e7ec5251680351c70b1eda74e308a', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332138018, 'a:11:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:9:"on_search";s:0:"";s:14:"search_keyword";s:0:"";s:18:"selected_client_id";s:0:"";s:3:"set";s:7:"default";}'),
('e429af8b5c19e2ab778397cc5f7692eb', '192.168.1.4', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332097464, ''),
('e1fb97b65f42968c1ec21e318a89fcd8', '192.168.1.3', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332097752, ''),
('01686898ca13f3d58c4ded9aa80cffca', '192.168.1.7', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332106784, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('bffce9b86c308cc3d4d35d6782b67a48', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332135064, 'a:12:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:9:"on_search";s:0:"";s:14:"search_keyword";s:0:"";s:18:"selected_client_id";s:2:"42";s:3:"set";s:7:"my_list";s:17:"flash:old:warning";s:99:"Please Select on the List or <a href=''admin/diagnose/add/42'' class=''add''>Diagnose</a> a this Client";}'),
('487289540a8fb47654a81fb4786784eb', '192.168.1.7', 'Mozilla/5.0 (Windows NT 6.1; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332106910, ''),
('463c6a2745eae04d6141adaf08a07b6e', '192.168.1.7', 'Mozilla/5.0 (Windows NT 6.1; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332106910, ''),
('4347ae77d912d7f2a2ac1ac2fd8e9f07', '192.168.1.7', 'Mozilla/5.0 (Windows NT 6.1; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332122622, 'a:11:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:18:"selected_client_id";s:0:"";s:3:"set";s:7:"default";s:9:"on_search";s:0:"";s:14:"search_keyword";s:0:"";}'),
('0acefbbaedda7886dc34f17d7c6456e7', '192.168.1.4', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:11.0) Gecko/20100101 Firefox/11.0', 1332128543, ''),
('f6a350764d590c3d012c0b1e3699dc28', '192.168.1.50', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332128231, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";}'),
('ea48c53b11e26997d6b03fce05c1f7ba', '192.168.1.7', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332135745, 'a:9:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:18:"selected_client_id";s:0:"";s:3:"set";s:7:"default";}'),
('980f2724b76f594023418c5048f80a8f', '192.168.1.7', 'Mozilla/5.0 (Windows NT 6.1; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332122622, ''),
('ffbb7591ca54cec13f1cf155fe1007b2', '192.168.1.7', 'Mozilla/5.0 (Windows NT 6.1; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332122622, ''),
('3797f6938e38dfb1e3b63d98875134ea', '192.168.1.7', 'Mozilla/5.0 (Windows NT 6.1; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332130027, 'a:10:{s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:18:"selected_client_id";s:0:"";s:3:"set";s:7:"default";s:9:"on_search";s:0:"";s:14:"search_keyword";s:0:"";}'),
('627d288b007102c1829089b28d57f27e', '192.168.1.3', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332127952, ''),
('631000a556d9e3c3a17c88a4ce31dd4f', '192.168.1.3', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332137138, 'a:11:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:18:"selected_client_id";s:2:"43";s:3:"set";s:7:"my_list";s:9:"on_search";s:0:"";s:14:"search_keyword";s:0:"";}'),
('f0ae7c898ac865bbbe03f63d61f22cee', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2', 1332142173, 'a:11:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:9:"on_search";s:0:"";s:14:"search_keyword";s:0:"";s:18:"selected_client_id";s:0:"";s:3:"set";s:7:"default";}'),
('81236c070298310fe1f277b2330c3cbf', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332232222, 'a:10:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:9:"on_search";s:0:"";s:14:"search_keyword";s:0:"";s:17:"flash:old:success";s:40:"6 clients out of 6 successfully deleted.";}'),
('7f3568bb3f8d094709affb0598350d83', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332271664, ''),
('7db19453d711fc9c1fcf328698fa1aef', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332274674, 'a:11:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:9:"on_search";s:0:"";s:14:"search_keyword";s:0:"";s:18:"selected_client_id";s:2:"47";s:3:"set";s:7:"my_list";}'),
('3a016a6a25987db88786fd0aca0b059c', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332305941, ''),
('63b3d105424c93adb49ffcb3214bbc12', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332281055, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}'),
('4b9ee2e34a73f2f3a772472561b2b127', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332297160, 'a:11:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:9:"on_search";s:0:"";s:14:"search_keyword";s:0:"";s:18:"selected_client_id";s:0:"";s:3:"set";s:7:"default";}'),
('bffc0617098018104cd413d5541a8f27', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; rv:11.0) Gecko/20100101 Firefox/11.0', 1332332075, 'a:11:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:9:"on_search";s:0:"";s:14:"search_keyword";s:0:"";s:18:"selected_client_id";s:0:"";s:3:"set";s:7:"default";}'),
('88106109d6853c864a502db567cbabbb', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332341692, 'a:11:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:9:"on_search";s:4:"true";s:14:"search_keyword";s:4:"test";s:18:"selected_client_id";s:0:"";s:3:"set";s:7:"default";}'),
('dcb0fb25550e6fded2d304e362414e2e', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; rv:11.0) Gecko/20100101 Firefox/11.0', 1332341683, ''),
('7319c09638c07d5ed719fd6c994ea64a', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332390109, 'a:11:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:9:"on_search";s:0:"";s:14:"search_keyword";s:0:"";s:18:"selected_client_id";s:0:"";s:3:"set";s:7:"default";}'),
('2e41dc69097f377ca3a74e19146bdab2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332395384, ''),
('63f37c8a20b2f8c2e099dcf79d80ab99', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332398050, ''),
('739d5e79389ca2adf21704e5d9cb9e39', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332398082, ''),
('4159f64b5811e6a721050bdea9489daf', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332398086, ''),
('4d35d604417623dd6e6d211d80767d03', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332399488, 'a:9:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:9:"on_search";s:0:"";s:14:"search_keyword";s:0:"";}'),
('f676f5557045f2ed5fe4f28d1f813e71', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332402047, 'a:8:{s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:9:"on_search";s:0:"";s:14:"search_keyword";s:0:"";}'),
('21bb2be809b4f25813874323771d5a38', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332418528, 'a:9:{s:9:"user_data";s:0:"";s:8:"username";s:4:"user";s:5:"email";s:12:"user@bhs.com";s:2:"id";s:1:"4";s:7:"user_id";s:1:"4";s:8:"group_id";s:1:"3";s:5:"group";s:5:"staff";s:9:"on_search";s:0:"";s:14:"search_keyword";s:0:"";}'),
('db38074c500b5b891fe78a4038896c25', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332419799, 'a:9:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";s:9:"on_search";s:0:"";s:14:"search_keyword";s:0:"";}'),
('8472fd1608402fa3ae78c4901175fb09', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332417188, 'a:10:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";s:9:"on_search";s:0:"";s:14:"search_keyword";s:0:"";s:17:"flash:old:success";s:44:"1 province(s) out of 1 successfully deleted.";}'),
('ebf9670b5b71704887daf807d81c11bc', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11', 1332422494, 'a:7:{s:9:"user_data";s:0:"";s:8:"username";s:5:"admin";s:5:"email";s:20:"ronaldrhey@gmail.com";s:2:"id";s:1:"1";s:7:"user_id";s:1:"1";s:8:"group_id";s:1:"1";s:5:"group";s:5:"admin";}');

-- --------------------------------------------------------

--
-- Table structure for table `default_clients`
--

CREATE TABLE IF NOT EXISTS `default_clients` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serial_number` varchar(20) DEFAULT NULL,
  `form_number` varchar(20) DEFAULT NULL,
  `photo` varchar(100) NOT NULL DEFAULT 'default.png',
  `last_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) NOT NULL,
  `age` varchar(3) DEFAULT '',
  `gender` enum('male','female') NOT NULL DEFAULT 'male',
  `dob` int(11) DEFAULT NULL,
  `phonenumber` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `bloodtype` varchar(10) DEFAULT NULL,
  `relation` varchar(30) NOT NULL,
  `history` text NOT NULL,
  `registration_date` int(11) NOT NULL,
  `facility` varchar(50) NOT NULL,
  `philhealth` varchar(100) DEFAULT NULL,
  `philhealth_type` varchar(20) DEFAULT NULL,
  `philhealth_sponsor` varchar(50) DEFAULT NULL,
  `residence` varchar(100) DEFAULT NULL,
  `address` varchar(300) DEFAULT NULL,
  `barangay_id` int(10) DEFAULT NULL,
  `city_id` int(10) DEFAULT NULL,
  `province_id` int(10) DEFAULT NULL,
  `region_id` int(10) DEFAULT NULL,
  `last_user_trans` int(10) NOT NULL,
  `ip_address` varchar(20) NOT NULL,
  `last_update` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `default_clients`
--


-- --------------------------------------------------------

--
-- Table structure for table `default_clients_barangays`
--

CREATE TABLE IF NOT EXISTS `default_clients_barangays` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `default_clients_barangays`
--

INSERT INTO `default_clients_barangays` (`id`, `name`) VALUES
(5, 'Upper Centro'),
(6, 'Sebac'),
(7, 'Centro Hulpa'),
(8, 'Taguima'),
(9, 'Napurog'),
(10, 'Centro Napo'),
(11, 'Barra'),
(12, 'Cabol-anonan'),
(13, 'Nailon'),
(14, 'Basirang'),
(15, 'Bongabong'),
(16, 'San Nicolas'),
(17, 'Camating'),
(18, 'Tigdok'),
(19, 'Cahayag'),
(20, 'Maikay'),
(21, 'Buenavista'),
(22, 'Tonggo'),
(23, 'Mitugas'),
(24, 'Colambutan Settlement'),
(25, 'Gala'),
(26, 'Gulbit'),
(27, 'Balon'),
(28, 'Duanguican'),
(29, 'Sinuza'),
(30, 'Canibungan Proper'),
(31, 'Casilak'),
(32, 'San Agustin'),
(33, 'Colambutan Bajo'),
(34, 'Maribojoc'),
(35, 'Locsoon'),
(36, 'Pan-ay  Diot'),
(37, 'Silongon'),
(38, 'Yahong');

-- --------------------------------------------------------

--
-- Table structure for table `default_clients_cities`
--

CREATE TABLE IF NOT EXISTS `default_clients_cities` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `default_clients_cities`
--

INSERT INTO `default_clients_cities` (`id`, `name`) VALUES
(2, 'Tudela');

-- --------------------------------------------------------

--
-- Table structure for table `default_clients_provinces`
--

CREATE TABLE IF NOT EXISTS `default_clients_provinces` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `default_clients_provinces`
--

INSERT INTO `default_clients_provinces` (`id`, `name`) VALUES
(1, 'Misamis Occidental');

-- --------------------------------------------------------

--
-- Table structure for table `default_clients_regions`
--

CREATE TABLE IF NOT EXISTS `default_clients_regions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `default_clients_regions`
--

INSERT INTO `default_clients_regions` (`id`, `name`) VALUES
(1, '10');

-- --------------------------------------------------------

--
-- Table structure for table `default_comments`
--

CREATE TABLE IF NOT EXISTS `default_comments` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `website` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `parsed` text COLLATE utf8_unicode_ci NOT NULL,
  `module` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `module_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `created_on` varchar(11) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `ip_address` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Comments by users or guests' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `default_comments`
--


-- --------------------------------------------------------

--
-- Table structure for table `default_consultations`
--

CREATE TABLE IF NOT EXISTS `default_consultations` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `client_id` int(10) NOT NULL,
  `cc` text NOT NULL,
  `wt` decimal(6,2) NOT NULL,
  `ht` decimal(6,2) NOT NULL,
  `bp` varchar(20) NOT NULL,
  `temp` decimal(6,2) NOT NULL,
  `pr` smallint(4) NOT NULL,
  `rr` smallint(4) NOT NULL,
  `objective` text,
  `plan` text NOT NULL,
  `referrer_id` int(10) NOT NULL,
  `client_age` int(3) NOT NULL,
  `date_consultations` int(11) NOT NULL,
  `date_added` int(11) NOT NULL,
  `added_by` int(10) NOT NULL,
  `last_update` int(11) DEFAULT NULL,
  `last_update_by` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `default_consultations`
--


-- --------------------------------------------------------

--
-- Table structure for table `default_contact_log`
--

CREATE TABLE IF NOT EXISTS `default_contact_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `sender_agent` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sender_ip` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sender_os` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sent_at` int(11) NOT NULL DEFAULT '0',
  `attachments` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Contact log' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `default_contact_log`
--


-- --------------------------------------------------------

--
-- Table structure for table `default_dental_health`
--

CREATE TABLE IF NOT EXISTS `default_dental_health` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `client_id` int(10) NOT NULL,
  `orally_fit` varchar(10) DEFAULT NULL,
  `date_given_bohc` int(11) DEFAULT NULL,
  `bohc_services` varchar(200) DEFAULT NULL,
  `remarks` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `default_dental_health`
--


-- --------------------------------------------------------

--
-- Table structure for table `default_diseases`
--

CREATE TABLE IF NOT EXISTS `default_diseases` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `category` smallint(3) NOT NULL DEFAULT '0',
  `description` text,
  `symptoms` text,
  `treatment` text,
  `date_added` int(11) NOT NULL,
  `added_by` int(10) NOT NULL,
  `last_update` int(11) DEFAULT NULL,
  `last_updated_by` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `default_diseases`
--

INSERT INTO `default_diseases` (`id`, `name`, `category`, `description`, `symptoms`, `treatment`, `date_added`, `added_by`, `last_update`, `last_updated_by`) VALUES
(1, 'Acute Flaccid Paralysis', 1, NULL, NULL, NULL, 1337394851, 4, 1337394861, 4),
(2, 'Adverse Event Following Immunization', 1, NULL, NULL, NULL, 1337394905, 4, NULL, NULL),
(3, 'Anthrax', 1, NULL, NULL, NULL, 1337394920, 4, NULL, NULL),
(4, 'Human Avian Influenza', 1, NULL, NULL, NULL, 1337394944, 4, NULL, NULL),
(5, 'Measles', 1, NULL, NULL, NULL, 1337394959, 4, NULL, NULL),
(6, 'Meningococcal Disease', 1, NULL, NULL, NULL, 1337394991, 4, NULL, NULL),
(7, 'Neonatal Tetanus', 1, NULL, NULL, NULL, 1337395004, 4, NULL, NULL),
(8, 'Paralytic Shellfish POisoning', 1, NULL, NULL, NULL, 1337395027, 4, NULL, NULL),
(9, 'Rabies', 1, NULL, NULL, NULL, 1337395041, 4, NULL, NULL),
(10, 'Severe Acute Respiratory Syndrome', 1, NULL, NULL, NULL, 1337395063, 4, NULL, NULL),
(11, 'Acute Bloody Diarrhea', 2, NULL, NULL, NULL, 1337395141, 4, NULL, NULL),
(12, 'Acute Encephalitis Syndrome', 2, NULL, NULL, NULL, 1337395187, 4, NULL, NULL),
(13, 'Acute Hemorrhagic Fever Syndrome', 2, NULL, NULL, NULL, 1337395267, 4, NULL, NULL),
(14, 'Acute Viral Hepatitis', 2, NULL, NULL, NULL, 1337395301, 4, NULL, NULL),
(15, 'Bacterial Meningitis', 2, NULL, NULL, NULL, 1337395322, 4, NULL, NULL),
(16, 'Cholera', 2, NULL, NULL, NULL, 1337395338, 4, NULL, NULL),
(17, 'Dengue', 2, NULL, NULL, NULL, 1337395352, 4, NULL, NULL),
(18, 'Diphtheria', 2, NULL, NULL, NULL, 1337395395, 4, NULL, NULL),
(19, 'Influenza-like Illness', 2, NULL, NULL, NULL, 1337395426, 4, NULL, NULL),
(20, 'Leptospirosis', 2, NULL, NULL, NULL, 1337395444, 4, NULL, NULL),
(21, 'Malaria', 2, NULL, NULL, NULL, 1337395456, 4, NULL, NULL),
(22, 'Non-neonatal Tetanus', 2, NULL, NULL, NULL, 1337395478, 4, NULL, NULL),
(23, 'Pertussis', 2, NULL, NULL, NULL, 1337395503, 4, NULL, NULL),
(24, 'Typhoid and Paratyphoid Fever', 2, NULL, NULL, NULL, 1337395546, 4, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `default_email_templates`
--

CREATE TABLE IF NOT EXISTS `default_email_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `lang` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_default` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_lang` (`slug`,`lang`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Store dynamic email templates' AUTO_INCREMENT=7 ;

--
-- Dumping data for table `default_email_templates`
--

INSERT INTO `default_email_templates` (`id`, `slug`, `name`, `description`, `subject`, `body`, `lang`, `is_default`) VALUES
(1, 'comments', 'Comment Notification', 'Email that is sent to admin when someone creates a comment', 'You have just received a comment from {{ name }}', '<h3>You have received a comment from {{ name }}</h3><strong>IP Address: {{ sender_ip }}</strong>\n<strong>Operating System: {{ sender_os }}\n<strong>User Agent: {{ sender_agent }}</strong>\n<div>{{ comment }}</div>\n<div>View Comment:{{ redirect_url }}</div>', 'en', 1),
(2, 'contact', 'Contact Notification', 'Template for the contact form', '{{ settings:site_name }} :: {{ subject }}', 'This message was sent via the contact form on with the following details:\n				<hr />\n				IP Address: {{ sender_ip }}\n				OS {{ sender_os }}\n				Agent {{ sender_agent }}\n				<hr />\n				{{ message }}\n\n				{{ name }},\n				{{ email }}', 'en', 1),
(3, 'registered', 'New User Registered', 'The email sent to the site contact e-mail when a new user registers', '{{ settings:site_name }} :: You have just received a registration from {{ name}', '<h3>You have received a registration from {{ name}</h3><strong>IP Address: {{ sender_ip }}</strong>\n				<strong>Operating System: {{ sender_os }}\n				<strong>User Agent: {{ sender_agent }}</strong>', 'en', 1),
(4, 'activation', 'Activation Email', 'The email which contains the activation code that is sent to a new user', '{{ settings:site_name }} - Account Activation', '<p>Hello {{ user:first_name }},</p>\n									<p>Thank you for registering at {{ settings:site_name }}. Before we can activate your account, please complete the registration process by clicking on the following link:</p>\n									<p><a href="{{ url:site }}users/activate/{{ user:id }}/{{ activation_code }}">{{ url:site }}users/activate/{{ user:id }}/{{ activation_code }}</a></p>\n									<p>&nbsp;</p>\n									<p>In case your email program does not recognize the above link as, please direct your browser to the following URL and enter the activation code:</p>\n									<p><a href="{{ url:site }}users/activate">{{ url:site }}users/activate</a></p>\n									<p><strong>Activation Code:</strong> {{ activation_code }}</p>', 'en', 1),
(5, 'forgotten_password', 'Forgotten Password Email', 'The email that is sent containing a password reset code', '{{ settings:site_name }} - Forgotten Password', '<p>Hello {{ user:first_name }},</p>\n									<p>It seems you have requested a password reset. Please click this link to complete the reset: <a href="{{ url:site }}users/reset_pass/{{ user:forgotten_password_code }}">{{ url:site }}users/reset_pass/{{ user:forgotten_password_code }}</a></p>\n									<p>If you did not request a password reset please disregard this message. No further action is necessary.</p>', 'en', 1),
(6, 'new_password', 'New Password Email', 'After a password is reset this email is sent containing the new password', '{{ settings:site_name }} - New Password', '<p>Hello {{ user:first_name }},</p>\n									<p>Your new password is: {{ new_password }}</p>\n									<p>After logging in you may change your password by visiting <a href="{{ url:site }}edit-profile">{{ url:site }}edit-profile</a></p>', 'en', 1);

-- --------------------------------------------------------

--
-- Table structure for table `default_environmental_health`
--

CREATE TABLE IF NOT EXISTS `default_environmental_health` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `client_id` int(10) NOT NULL,
  `date_conducted` int(11) DEFAULT NULL,
  `hh_safe_water` varchar(10) DEFAULT NULL,
  `hh_safe_water_level` varchar(20) DEFAULT NULL,
  `hh_sanitary_toilet` varchar(10) DEFAULT NULL,
  `hh_satisfactory_waste_disposal` varchar(10) DEFAULT NULL,
  `hh_complete_sanitation_facility` varchar(10) DEFAULT NULL,
  `food_establishment` varchar(10) DEFAULT NULL,
  `food_establishment_sanitary_permit` varchar(10) DEFAULT NULL,
  `food_handler` varchar(10) DEFAULT NULL,
  `food_handler_health_certificate` varchar(10) DEFAULT NULL,
  `salt_sample_tested` varchar(10) DEFAULT NULL,
  `salt_sample_iodine` varchar(10) DEFAULT NULL,
  `remarks` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `default_environmental_health`
--


-- --------------------------------------------------------

--
-- Table structure for table `default_family_planning`
--

CREATE TABLE IF NOT EXISTS `default_family_planning` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `client_id` int(10) NOT NULL,
  `client_type` varchar(20) NOT NULL DEFAULT '',
  `previous_method_id` int(10) DEFAULT NULL,
  `method_id` int(10) NOT NULL,
  `date_started` int(11) NOT NULL,
  `drop_out_reason` varchar(100) DEFAULT NULL,
  `drop_out_date` int(11) DEFAULT NULL,
  `remarks` text,
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `default_family_planning`
--


-- --------------------------------------------------------

--
-- Table structure for table `default_family_planning_methods`
--

CREATE TABLE IF NOT EXISTS `default_family_planning_methods` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `code` varchar(5) DEFAULT NULL,
  `method` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `default_family_planning_methods`
--

INSERT INTO `default_family_planning_methods` (`id`, `code`, `method`) VALUES
(1, 'CON', 'Condom'),
(2, 'INJ', 'Depo-Provera(DMPA)'),
(3, 'IUD', 'Intra-uterine Device'),
(4, 'PILLS', 'Pills'),
(5, 'NFP-B', 'Basal Body Temp'),
(6, 'NFP-C', 'Cervical Mucus Method'),
(7, 'NFP-S', 'Symtothermal Method'),
(8, 'NFP-L', 'Lactational Amenorrhea Method'),
(9, 'NFP-S', 'Standard Days Method'),
(10, 'MSTR/', 'Male Ster/Vasectomy'),
(11, 'FSTR/', 'Female Ster/Bilateral Tubal Ligation');

-- --------------------------------------------------------

--
-- Table structure for table `default_family_planning_visits`
--

CREATE TABLE IF NOT EXISTS `default_family_planning_visits` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fp_id` int(10) NOT NULL,
  `category` tinyint(2) NOT NULL,
  `service_date` int(11) DEFAULT NULL,
  `accomplished_date` int(11) DEFAULT NULL,
  `method_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fp_id` (`fp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `default_family_planning_visits`
--


-- --------------------------------------------------------

--
-- Table structure for table `default_files`
--

CREATE TABLE IF NOT EXISTS `default_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `folder_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '1',
  `type` enum('a','v','d','i','o') COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `extension` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `mimetype` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `width` int(5) DEFAULT NULL,
  `height` int(5) DEFAULT NULL,
  `filesize` int(11) NOT NULL DEFAULT '0',
  `date_added` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `default_files`
--


-- --------------------------------------------------------

--
-- Table structure for table `default_file_folders`
--

CREATE TABLE IF NOT EXISTS `default_file_folders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT '0',
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date_added` int(11) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `default_file_folders`
--

INSERT INTO `default_file_folders` (`id`, `parent_id`, `slug`, `name`, `date_added`, `sort`) VALUES
(1, 0, 'mkwlejfihnefl', 'first', 1329186699, 0);

-- --------------------------------------------------------

--
-- Table structure for table `default_groups`
--

CREATE TABLE IF NOT EXISTS `default_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Permission roles such as admins, moderators, staff, etc' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `default_groups`
--

INSERT INTO `default_groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrators'),
(2, 'user', 'Users'),
(3, 'staff', 'Staff'),
(4, 'dental', 'Dental'),
(5, 'sanitary', 'Sanitary');

-- --------------------------------------------------------

--
-- Table structure for table `default_keywords`
--

CREATE TABLE IF NOT EXISTS `default_keywords` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `default_keywords`
--

INSERT INTO `default_keywords` (`id`, `name`) VALUES
(1, 'add');

-- --------------------------------------------------------

--
-- Table structure for table `default_keywords_applied`
--

CREATE TABLE IF NOT EXISTS `default_keywords_applied` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hash` char(32) NOT NULL,
  `keyword_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `default_keywords_applied`
--


-- --------------------------------------------------------

--
-- Table structure for table `default_migrations`
--

CREATE TABLE IF NOT EXISTS `default_migrations` (
  `version` int(3) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `default_migrations`
--

INSERT INTO `default_migrations` (`version`) VALUES
(70);

-- --------------------------------------------------------

--
-- Table structure for table `default_modules`
--

CREATE TABLE IF NOT EXISTS `default_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `version` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `skip_xss` tinyint(1) NOT NULL,
  `is_frontend` tinyint(1) NOT NULL,
  `is_backend` tinyint(1) NOT NULL,
  `menu` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `installed` tinyint(1) NOT NULL,
  `is_core` tinyint(1) NOT NULL,
  `updated_on` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=43 ;

--
-- Dumping data for table `default_modules`
--

INSERT INTO `default_modules` (`id`, `name`, `slug`, `version`, `type`, `description`, `skip_xss`, `is_frontend`, `is_backend`, `menu`, `enabled`, `installed`, `is_core`, `updated_on`) VALUES
(1, 'a:9:{s:2:"en";s:4:"Blog";s:2:"ar";s:16:"المدوّنة";s:2:"el";s:18:"Ιστολόγιο";s:2:"br";s:4:"Blog";s:2:"pl";s:4:"Blog";s:2:"he";s:8:"בלוג";s:2:"lt";s:6:"Blogas";s:2:"ru";s:8:"Блог";s:2:"zh";s:6:"文章";}', 'blog', '2.0', NULL, 'a:18:{s:2:"en";s:18:"Post blog entries.";s:2:"nl";s:41:"Post nieuwsartikelen en blogs op uw site.";s:2:"es";s:54:"Escribe entradas para los artículos y blog (web log).";s:2:"fr";s:46:"Envoyez de nouveaux posts et messages de blog.";s:2:"de";s:47:"Veröffentliche neue Artikel und Blog-Einträge";s:2:"pl";s:27:"Dodawaj nowe wpisy na blogu";s:2:"br";s:30:"Escrever publicações de blog";s:2:"zh";s:42:"發表新聞訊息、部落格等文章。";s:2:"it";s:36:"Pubblica notizie e post per il blog.";s:2:"ru";s:49:"Управление записями блога.";s:2:"ar";s:48:"أنشر المقالات على مدوّنتك.";s:2:"cs";s:49:"Publikujte nové články a příspěvky na blog.";s:2:"sl";s:23:"Objavite blog prispevke";s:2:"fi";s:50:"Kirjoita uutisartikkeleita tai blogi artikkeleita.";s:2:"el";s:93:"Δημιουργήστε άρθρα και εγγραφές στο ιστολόγιο σας.";s:2:"he";s:19:"ניהול בלוג";s:2:"lt";s:40:"Rašykite naujienas bei blog''o įrašus.";s:2:"da";s:17:"Skriv blogindlæg";}', 1, 1, 1, 'content', 1, 1, 1, 1327041147),
(2, 'a:18:{s:2:"sl";s:10:"Komentarji";s:2:"en";s:8:"Comments";s:2:"br";s:12:"Comentários";s:2:"nl";s:8:"Reacties";s:2:"es";s:11:"Comentarios";s:2:"fr";s:12:"Commentaires";s:2:"de";s:10:"Kommentare";s:2:"pl";s:10:"Komentarze";s:2:"zh";s:6:"回應";s:2:"it";s:8:"Commenti";s:2:"ru";s:22:"Комментарии";s:2:"ar";s:18:"التعليقات";s:2:"cs";s:11:"Komentáře";s:2:"fi";s:9:"Kommentit";s:2:"el";s:12:"Σχόλια";s:2:"he";s:12:"תגובות";s:2:"lt";s:10:"Komentarai";s:2:"da";s:11:"Kommentarer";}', 'comments', '1.0', NULL, 'a:18:{s:2:"sl";s:89:"Uporabniki in obiskovalci lahko vnesejo komentarje na vsebino kot je blok, stra ali slike";s:2:"en";s:76:"Users and guests can write comments for content like blog, pages and photos.";s:2:"br";s:97:"Usuários e convidados podem escrever comentários para quase tudo com suporte nativo ao captcha.";s:2:"nl";s:52:"Gebruikers en gasten kunnen reageren op bijna alles.";s:2:"es";s:130:"Los usuarios y visitantes pueden escribir comentarios en casi todo el contenido con el soporte de un sistema de captcha incluído.";s:2:"fr";s:130:"Les utilisateurs et les invités peuvent écrire des commentaires pour quasiment tout grâce au générateur de captcha intégré.";s:2:"de";s:65:"Benutzer und Gäste können für fast alles Kommentare schreiben.";s:2:"pl";s:93:"Użytkownicy i goście mogą dodawać komentarze z wbudowanym systemem zabezpieczeń captcha.";s:2:"zh";s:75:"用戶和訪客可以針對新聞、頁面與照片等內容發表回應。";s:2:"it";s:85:"Utenti e visitatori possono scrivere commenti ai contenuti quali blog, pagine e foto.";s:2:"ru";s:187:"Пользователи и гости могут добавлять комментарии к новостям, информационным страницам и фотографиям.";s:2:"ar";s:152:"يستطيع الأعضاء والزوّار كتابة التعليقات على المُحتوى كالأخبار، والصفحات والصّوَر.";s:2:"cs";s:100:"Uživatelé a hosté mohou psát komentáře k obsahu, např. neovinkám, stránkám a fotografiím.";s:2:"fi";s:107:"Käyttäjät ja vieraat voivat kirjoittaa kommentteja eri sisältöihin kuten uutisiin, sivuihin ja kuviin.";s:2:"el";s:224:"Οι χρήστες και οι επισκέπτες μπορούν να αφήνουν σχόλια για περιεχόμενο όπως το ιστολόγιο, τις σελίδες και τις φωτογραφίες.";s:2:"he";s:94:"משתמשי האתר יכולים לרשום תגובות למאמרים, תמונות וכו";s:2:"lt";s:75:"Vartotojai ir svečiai gali komentuoti jūsų naujienas, puslapius ar foto.";s:2:"da";s:83:"Brugere og besøgende kan skrive kommentarer til indhold som blog, sider og fotoer.";}', 0, 0, 1, 'content', 1, 1, 1, 1327041147),
(3, 'a:18:{s:2:"sl";s:7:"Kontakt";s:2:"en";s:7:"Contact";s:2:"nl";s:7:"Contact";s:2:"pl";s:7:"Kontakt";s:2:"es";s:8:"Contacto";s:2:"fr";s:7:"Contact";s:2:"de";s:7:"Kontakt";s:2:"zh";s:12:"聯絡我們";s:2:"it";s:10:"Contattaci";s:2:"ru";s:27:"Обратная связь";s:2:"ar";s:14:"الإتصال";s:2:"br";s:7:"Contato";s:2:"cs";s:7:"Kontakt";s:2:"fi";s:13:"Ota yhteyttä";s:2:"el";s:22:"Επικοινωνία";s:2:"he";s:17:"יצירת קשר";s:2:"lt";s:18:"Kontaktinė formą";s:2:"da";s:7:"Kontakt";}', 'contact', '0.9', NULL, 'a:18:{s:2:"sl";s:113:"Dodaj obrazec za kontakt da vam lahko obiskovalci pošljejo sporočilo brez da bi jim razkrili vaš email naslov.";s:2:"en";s:112:"Adds a form to your site that allows visitors to send emails to you without disclosing an email address to them.";s:2:"nl";s:125:"Voegt een formulier aan de site toe waarmee bezoekers een email kunnen sturen, zonder dat u ze een emailadres hoeft te tonen.";s:2:"pl";s:126:"Dodaje formularz kontaktowy do Twojej strony, który pozwala użytkownikom wysłanie maila za pomocą formularza kontaktowego.";s:2:"es";s:156:"Añade un formulario a tu sitio que permitirá a los visitantes enviarte correos electrónicos a ti sin darles tu dirección de correo directamente a ellos.";s:2:"fr";s:122:"Ajoute un formulaire à votre site qui permet aux visiteurs de vous envoyer un e-mail sans révéler votre adresse e-mail.";s:2:"de";s:119:"Fügt ein Formular hinzu, welches Besuchern erlaubt Emails zu schreiben, ohne die Kontakt Email-Adresse offen zu legen.";s:2:"zh";s:147:"為您的網站新增「聯絡我們」的功能，對訪客是較為清楚便捷的聯絡方式，也無須您將電子郵件公開在網站上。";s:2:"it";s:119:"Aggiunge un modulo al tuo sito che permette ai visitatori di inviarti email senza mostrare loro il tuo indirizzo email.";s:2:"ru";s:234:"Добавляет форму обратной связи на сайт, через которую посетители могут отправлять вам письма, при этом адрес Email остаётся скрыт.";s:2:"ar";s:157:"إضافة استمارة إلى موقعك تُمكّن الزوّار من مراسلتك دون علمهم بعنوان البريد الإلكتروني.";s:2:"br";s:139:"Adiciona um formulário para o seu site permitir aos visitantes que enviem e-mails para voce sem divulgar um endereço de e-mail para eles.";s:2:"cs";s:149:"Přidá na web kontaktní formulář pro návštěvníky a uživatele, díky kterému vás mohou kontaktovat i bez znalosti vaší e-mailové adresy.";s:2:"fi";s:128:"Luo lomakkeen sivustollesi, josta kävijät voivat lähettää sähköpostia tietämättä vastaanottajan sähköpostiosoitetta.";s:2:"el";s:273:"Προσθέτει μια φόρμα στον ιστότοπό σας που επιτρέπει σε επισκέπτες να σας στέλνουν μηνύμα μέσω email χωρίς να τους αποκαλύπτεται η διεύθυνση του email σας.";s:2:"he";s:155:"מוסיף תופס יצירת קשר לאתר על מנת לא לחסוף כתובת דואר האלקטרוני של האתר למנועי פרסומות";s:2:"lt";s:124:"Prideda jūsų puslapyje formą leidžianti lankytojams siūsti jums el. laiškus neatskleidžiant jūsų el. pašto adreso.";s:2:"da";s:123:"Tilføjer en formular på din side som tillader besøgende at sende mails til dig, uden at du skal opgive din email-adresse";}', 0, 0, 0, '0', 1, 1, 1, 1327041147),
(4, 'a:17:{s:2:"sl";s:8:"Datoteke";s:2:"en";s:5:"Files";s:2:"br";s:8:"Arquivos";s:2:"de";s:7:"Dateien";s:2:"nl";s:9:"Bestanden";s:2:"fr";s:8:"Fichiers";s:2:"zh";s:6:"檔案";s:2:"it";s:4:"File";s:2:"ru";s:10:"Файлы";s:2:"ar";s:16:"الملفّات";s:2:"cs";s:7:"Soubory";s:2:"es";s:8:"Archivos";s:2:"fi";s:9:"Tiedostot";s:2:"el";s:12:"Αρχεία";s:2:"he";s:10:"קבצים";s:2:"lt";s:6:"Failai";s:2:"da";s:5:"Filer";}', 'files', '1.2', NULL, 'a:17:{s:2:"sl";s:38:"Uredi datoteke in mape na vaši strani";s:2:"en";s:40:"Manages files and folders for your site.";s:2:"br";s:53:"Permite gerenciar facilmente os arquivos de seu site.";s:2:"de";s:35:"Verwalte Dateien und Verzeichnisse.";s:2:"nl";s:41:"Beheer bestanden en mappen op uw website.";s:2:"fr";s:46:"Gérer les fichiers et dossiers de votre site.";s:2:"zh";s:33:"管理網站中的檔案與目錄";s:2:"it";s:38:"Gestisci file e cartelle del tuo sito.";s:2:"ru";s:78:"Управление файлами и папками вашего сайта.";s:2:"ar";s:50:"إدارة ملفات ومجلّدات موقعك.";s:2:"cs";s:43:"Spravujte soubory a složky na vašem webu.";s:2:"es";s:43:"Administra archivos y carpetas en tu sitio.";s:2:"fi";s:43:"Hallitse sivustosi tiedostoja ja kansioita.";s:2:"el";s:100:"Διαχειρίζεται αρχεία και φακέλους για το ιστότοπό σας.";s:2:"he";s:47:"ניהול תיקיות וקבצים שבאתר";s:2:"lt";s:28:"Katalogų ir bylų valdymas.";s:2:"da";s:41:"Administrer filer og mapper for dit site.";}', 0, 0, 1, 'content', 1, 1, 1, 1327041147),
(5, 'a:17:{s:2:"sl";s:7:"Skupine";s:2:"en";s:6:"Groups";s:2:"br";s:6:"Grupos";s:2:"de";s:7:"Gruppen";s:2:"nl";s:7:"Groepen";s:2:"fr";s:7:"Groupes";s:2:"zh";s:6:"群組";s:2:"it";s:6:"Gruppi";s:2:"ru";s:12:"Группы";s:2:"ar";s:18:"المجموعات";s:2:"cs";s:7:"Skupiny";s:2:"es";s:6:"Grupos";s:2:"fi";s:7:"Ryhmät";s:2:"el";s:12:"Ομάδες";s:2:"he";s:12:"קבוצות";s:2:"lt";s:7:"Grupės";s:2:"da";s:7:"Grupper";}', 'groups', '1.0', NULL, 'a:17:{s:2:"sl";s:64:"Uporabniki so lahko razvrščeni v skupine za urejanje dovoljenj";s:2:"en";s:54:"Users can be placed into groups to manage permissions.";s:2:"br";s:72:"Usuários podem ser inseridos em grupos para gerenciar suas permissões.";s:2:"de";s:85:"Benutzer können zu Gruppen zusammengefasst werden um diesen Zugriffsrechte zu geben.";s:2:"nl";s:73:"Gebruikers kunnen in groepen geplaatst worden om rechten te kunnen geven.";s:2:"fr";s:82:"Les utilisateurs peuvent appartenir à des groupes afin de gérer les permissions.";s:2:"zh";s:45:"用戶可以依群組分類並管理其權限";s:2:"it";s:69:"Gli utenti possono essere inseriti in gruppi per gestirne i permessi.";s:2:"ru";s:134:"Пользователей можно объединять в группы, для управления правами доступа.";s:2:"ar";s:100:"يمكن وضع المستخدمين في مجموعات لتسهيل إدارة صلاحياتهم.";s:2:"cs";s:77:"Uživatelé mohou být rozřazeni do skupin pro lepší správu oprávnění.";s:2:"es";s:75:"Los usuarios podrán ser colocados en grupos para administrar sus permisos.";s:2:"fi";s:84:"Käyttäjät voidaan liittää ryhmiin, jotta käyttöoikeuksia voidaan hallinnoida.";s:2:"el";s:159:"Οι χρήστες μπορούν να τοποθετηθούν σε ομάδες και να διαχειριστείτε τα δικαιώματά τους.";s:2:"he";s:62:"נותן אפשרות לאסוף משתמשים לקבוצות";s:2:"lt";s:67:"Vartotojai gali būti priskirti grupei tam, kad valdyti jų teises.";s:2:"da";s:49:"Brugere kan inddeles i grupper for adgangskontrol";}', 0, 0, 1, 'users', 1, 1, 1, 1327041147),
(6, 'a:8:{s:2:"en";s:8:"Keywords";s:2:"el";s:27:"Λέξεις Κλειδιά";s:2:"fr";s:10:"Mots-Clés";s:2:"nl";s:14:"Sleutelwoorden";s:2:"ar";s:21:"كلمات البحث";s:2:"br";s:14:"Palavras-chave";s:2:"da";s:9:"Nøgleord";s:2:"zh";s:6:"鍵詞";}', 'keywords', '1.0', NULL, 'a:8:{s:2:"en";s:71:"Maintain a central list of keywords to label and organize your content.";s:2:"el";s:181:"Συντηρεί μια κεντρική λίστα από λέξεις κλειδιά για να οργανώνετε μέσω ετικετών το περιεχόμενό σας.";s:2:"fr";s:87:"Maintenir une liste centralisée de Mots-Clés pour libeller et organiser vos contenus.";s:2:"nl";s:91:"Beheer een centrale lijst van sleutelwoorden om uw content te categoriseren en organiseren.";s:2:"ar";s:124:"أنشئ مجموعة من كلمات البحث التي تستطيع من خلالها وسم وتنظيم المحتوى.";s:2:"br";s:85:"Mantém uma lista central de palavras-chave para rotular e organizar o seu conteúdo.";s:2:"da";s:72:"Vedligehold en central liste af nøgleord for at organisere dit indhold.";s:2:"zh";s:64:"集中管理可用於標題與內容的鍵詞(keywords)列表。";}', 0, 0, 1, 'content', 1, 1, 1, 1327041147),
(7, 'a:5:{s:2:"en";s:11:"Maintenance";s:2:"fr";s:11:"Maintenance";s:2:"el";s:18:"Συντήρηση";s:2:"ar";s:14:"الصيانة";s:2:"zh";s:6:"維護";}', 'maintenance', '1.0', NULL, 'a:5:{s:2:"en";s:63:"Manage the site cache and export information from the database.";s:2:"fr";s:71:"Gérer le cache du site et exporter les contenus de la base de données";s:2:"el";s:140:"Διαγραφή αντικειμένων προσωρινής αποθήκευσης μέσω της σελίδας διαχείρισης.";s:2:"ar";s:81:"حذف عناصر الذاكرة المخبأة عبر واجهة الإدارة.";s:2:"zh";s:45:"經由管理介面手動刪除暫存資料。";}', 0, 0, 1, 'utilities', 1, 1, 1, 1327041147),
(8, 'a:18:{s:2:"sl";s:6:"Moduli";s:2:"en";s:7:"Modules";s:2:"nl";s:7:"Modules";s:2:"es";s:8:"Módulos";s:2:"fr";s:7:"Modules";s:2:"de";s:6:"Module";s:2:"pl";s:7:"Moduły";s:2:"br";s:8:"Módulos";s:2:"zh";s:6:"模組";s:2:"it";s:6:"Moduli";s:2:"ru";s:12:"Модули";s:2:"ar";s:14:"الوحدات";s:2:"cs";s:6:"Moduly";s:2:"fi";s:8:"Moduulit";s:2:"el";s:16:"Πρόσθετα";s:2:"he";s:14:"מודולים";s:2:"lt";s:8:"Moduliai";s:2:"da";s:7:"Moduler";}', 'modules', '1.0', NULL, 'a:18:{s:2:"sl";s:65:"Dovoljuje administratorjem pregled trenutno nameščenih modulov.";s:2:"en";s:59:"Allows admins to see a list of currently installed modules.";s:2:"nl";s:79:"Stelt admins in staat om een overzicht van geinstalleerde modules te genereren.";s:2:"es";s:71:"Permite a los administradores ver una lista de los módulos instalados.";s:2:"fr";s:66:"Permet aux administrateurs de voir la liste des modules installés";s:2:"de";s:56:"Zeigt Administratoren alle aktuell installierten Module.";s:2:"pl";s:81:"Umożliwiają administratorowi wgląd do listy obecnie zainstalowanych modułów.";s:2:"br";s:75:"Permite aos administradores ver a lista dos módulos instalados atualmente.";s:2:"zh";s:54:"管理員可以檢視目前已經安裝模組的列表";s:2:"it";s:83:"Permette agli amministratori di vedere una lista dei moduli attualmente installati.";s:2:"ru";s:83:"Список модулей, которые установлены на сайте.";s:2:"ar";s:91:"تُمكّن المُدراء من معاينة جميع الوحدات المُثبّتة.";s:2:"cs";s:68:"Umožňuje administrátorům vidět seznam nainstalovaných modulů.";s:2:"fi";s:60:"Listaa järjestelmänvalvojalle käytössä olevat moduulit.";s:2:"el";s:152:"Επιτρέπει στους διαχειριστές να προβάλουν μια λίστα των εγκατεστημένων πρόσθετων.";s:2:"he";s:160:"נותן אופציה למנהל לראות רשימה של המודולים אשר מותקנים כעת באתר או להתקין מודולים נוספים";s:2:"lt";s:75:"Vartotojai ir svečiai gali komentuoti jūsų naujienas, puslapius ar foto.";s:2:"da";s:63:"Lader administratorer se en liste over de installerede moduler.";}', 0, 0, 1, '0', 1, 1, 1, 1327041147),
(9, 'a:17:{s:2:"sl";s:10:"Navigacija";s:2:"en";s:10:"Navigation";s:2:"nl";s:9:"Navigatie";s:2:"es";s:11:"Navegación";s:2:"fr";s:10:"Navigation";s:2:"de";s:10:"Navigation";s:2:"pl";s:9:"Nawigacja";s:2:"br";s:11:"Navegação";s:2:"zh";s:12:"導航選單";s:2:"it";s:11:"Navigazione";s:2:"ru";s:18:"Навигация";s:2:"ar";s:14:"الروابط";s:2:"cs";s:8:"Navigace";s:2:"fi";s:10:"Navigointi";s:2:"el";s:16:"Πλοήγηση";s:2:"he";s:10:"ניווט";s:2:"lt";s:10:"Navigacija";}', 'navigation', '1.1', NULL, 'a:18:{s:2:"sl";s:64:"Uredi povezave v meniju in vse skupine povezav ki jim pripadajo.";s:2:"en";s:78:"Manage links on navigation menus and all the navigation groups they belong to.";s:2:"nl";s:92:"Beheer koppelingen op de navigatiemenu&apos;s en alle navigatiegroepen waar ze onder vallen.";s:2:"es";s:102:"Administra links en los menús de navegación y en todos los grupos de navegación al cual pertenecen.";s:2:"fr";s:97:"Gérer les liens du menu Navigation et tous les groupes de navigation auxquels ils appartiennent.";s:2:"de";s:76:"Verwalte Links in Navigationsmenüs und alle zugehörigen Navigationsgruppen";s:2:"pl";s:95:"Zarządzaj linkami w menu nawigacji oraz wszystkimi grupami nawigacji do których one należą.";s:2:"br";s:91:"Gerenciar links do menu de navegação e todos os grupos de navegação pertencentes a ele.";s:2:"zh";s:72:"管理導航選單中的連結，以及它們所隸屬的導航群組。";s:2:"it";s:97:"Gestisci i collegamenti dei menu di navigazione e tutti i gruppi di navigazione da cui dipendono.";s:2:"ru";s:136:"Управление ссылками в меню навигации и группах, к которым они принадлежат.";s:2:"ar";s:85:"إدارة روابط وقوائم ومجموعات الروابط في الموقع.";s:2:"cs";s:73:"Správa odkazů v navigaci a všech souvisejících navigačních skupin.";s:2:"fi";s:91:"Hallitse linkkejä navigointi valikoissa ja kaikkia navigointi ryhmiä, joihin ne kuuluvat.";s:2:"el";s:207:"Διαχειριστείτε τους συνδέσμους στα μενού πλοήγησης και όλες τις ομάδες συνδέσμων πλοήγησης στις οποίες ανήκουν.";s:2:"he";s:73:"ניהול שלוחות תפריטי ניווט וקבוצות ניווט";s:2:"lt";s:95:"Tvarkyk nuorodas navigacijų menių ir visas navigacijų grupes kurioms tos nuorodos priklauso.";s:2:"da";s:82:"Håndtér links på navigationsmenuerne og alle navigationsgrupperne de tilhører.";}', 0, 0, 1, 'design', 1, 1, 1, 1327041147),
(10, 'a:18:{s:2:"sl";s:6:"Strani";s:2:"en";s:5:"Pages";s:2:"nl";s:13:"Pagina&apos;s";s:2:"es";s:8:"Páginas";s:2:"fr";s:5:"Pages";s:2:"de";s:6:"Seiten";s:2:"pl";s:6:"Strony";s:2:"br";s:8:"Páginas";s:2:"zh";s:6:"頁面";s:2:"it";s:6:"Pagine";s:2:"ru";s:16:"Страницы";s:2:"ar";s:14:"الصفحات";s:2:"cs";s:8:"Stránky";s:2:"fi";s:5:"Sivut";s:2:"el";s:14:"Σελίδες";s:2:"he";s:8:"דפים";s:2:"lt";s:9:"Puslapiai";s:2:"da";s:5:"Sider";}', 'pages', '2.0', NULL, 'a:18:{s:2:"sl";s:44:"Dodaj stran s kakršno koli vsebino želite.";s:2:"en";s:55:"Add custom pages to the site with any content you want.";s:2:"nl";s:70:"Voeg aangepaste pagina&apos;s met willekeurige inhoud aan de site toe.";s:2:"pl";s:53:"Dodaj własne strony z dowolną treścią do witryny.";s:2:"es";s:77:"Agrega páginas customizadas al sitio con cualquier contenido que tu quieras.";s:2:"fr";s:89:"Permet d''ajouter sur le site des pages personalisées avec le contenu que vous souhaitez.";s:2:"de";s:49:"Füge eigene Seiten mit anpassbaren Inhalt hinzu.";s:2:"br";s:82:"Adicionar páginas personalizadas ao site com qualquer conteúdo que você queira.";s:2:"zh";s:39:"為您的網站新增自定的頁面。";s:2:"it";s:73:"Aggiungi pagine personalizzate al sito con qualsiesi contenuto tu voglia.";s:2:"ru";s:134:"Управление информационными страницами сайта, с произвольным содержимым.";s:2:"ar";s:99:"إضافة صفحات مُخصّصة إلى الموقع تحتوي أية مُحتوى تريده.";s:2:"cs";s:74:"Přidávejte vlastní stránky na web s jakýmkoliv obsahem budete chtít.";s:2:"fi";s:47:"Lisää mitä tahansa sisältöä sivustollesi.";s:2:"el";s:134:"Προσθέστε δικές σας σελίδες στον ιστότοπό σας με ό,τι περιεχόμενο θέλετε.";s:2:"he";s:35:"ניהול דפי תוכן האתר";s:2:"lt";s:46:"Pridėkite nuosavus puslapius betkokio turinio";s:2:"da";s:71:"Tilføj brugerdefinerede sider til dit site med det indhold du ønsker.";}', 1, 1, 1, 'content', 1, 1, 1, 1327041147),
(11, 'a:18:{s:2:"sl";s:10:"Dovoljenja";s:2:"en";s:11:"Permissions";s:2:"nl";s:15:"Toegangsrechten";s:2:"es";s:8:"Permisos";s:2:"fr";s:11:"Permissions";s:2:"de";s:14:"Zugriffsrechte";s:2:"pl";s:11:"Uprawnienia";s:2:"br";s:11:"Permissões";s:2:"zh";s:6:"權限";s:2:"it";s:8:"Permessi";s:2:"ru";s:25:"Права доступа";s:2:"ar";s:18:"الصلاحيات";s:2:"cs";s:12:"Oprávnění";s:2:"fi";s:16:"Käyttöoikeudet";s:2:"el";s:20:"Δικαιώματα";s:2:"he";s:12:"הרשאות";s:2:"lt";s:7:"Teisės";s:2:"da";s:14:"Adgangskontrol";}', 'permissions', '0.5', NULL, 'a:18:{s:2:"sl";s:85:"Uredite dovoljenja kateri tip uporabnika lahko vidi določena področja vaše strani.";s:2:"en";s:68:"Control what type of users can see certain sections within the site.";s:2:"nl";s:71:"Bepaal welke typen gebruikers toegang hebben tot gedeeltes van de site.";s:2:"pl";s:79:"Ustaw, którzy użytkownicy mogą mieć dostęp do odpowiednich sekcji witryny.";s:2:"es";s:81:"Controla que tipo de usuarios pueden ver secciones específicas dentro del sitio.";s:2:"fr";s:104:"Permet de définir les autorisations des groupes d''utilisateurs pour afficher les différentes sections.";s:2:"de";s:70:"Regelt welche Art von Benutzer welche Sektion in der Seite sehen kann.";s:2:"br";s:68:"Controle quais tipos de usuários podem ver certas seções no site.";s:2:"zh";s:81:"用來控制不同類別的用戶，設定其瀏覽特定網站內容的權限。";s:2:"it";s:78:"Controlla che tipo di utenti posssono accedere a determinate sezioni del sito.";s:2:"ru";s:209:"Управление правами доступа, ограничение доступа определённых групп пользователей к произвольным разделам сайта.";s:2:"ar";s:127:"التحكم بإعطاء الصلاحيات للمستخدمين للوصول إلى أقسام الموقع المختلفة.";s:2:"cs";s:93:"Spravujte oprávnění pro jednotlivé typy uživatelů a ke kterým sekcím mají přístup.";s:2:"fi";s:72:"Hallitse minkä tyyppisiin osioihin käyttäjät pääsevät sivustolla.";s:2:"el";s:142:"Ελέγξτε οι χρήστες ποιας ομάδας μπορούν να δούν ποιες περιοχές του ιστοτόπου.";s:2:"he";s:75:"ניהול הרשאות כניסה לאיזורים מסוימים באתר";s:2:"lt";s:72:"Kontroliuokite kokio tipo varotojai kokią dalį puslapio gali pasiekti.";s:2:"da";s:72:"Kontroller hvilken type brugere der kan se bestemte sektioner på sitet.";}', 0, 0, 1, 'users', 1, 1, 1, 1327041147),
(12, 'a:16:{s:2:"sl";s:12:"Preusmeritve";s:2:"nl";s:12:"Verwijzingen";s:2:"en";s:9:"Redirects";s:2:"es";s:13:"Redirecciones";s:2:"fr";s:12:"Redirections";s:2:"it";s:11:"Reindirizzi";s:2:"ru";s:30:"Перенаправления";s:2:"ar";s:18:"التوجيهات";s:2:"br";s:17:"Redirecionamentos";s:2:"cs";s:16:"Přesměrování";s:2:"fi";s:18:"Uudelleenohjaukset";s:2:"el";s:30:"Ανακατευθύνσεις";s:2:"he";s:12:"הפניות";s:2:"lt";s:14:"Peradresavimai";s:2:"da";s:13:"Omadressering";s:2:"zh";s:6:"轉址";}', 'redirects', '1.0', NULL, 'a:16:{s:2:"sl";s:44:"Preusmeritev iz enega URL naslova na drugega";s:2:"nl";s:38:"Verwijs vanaf een URL naar een andere.";s:2:"en";s:33:"Redirect from one URL to another.";s:2:"es";s:34:"Redireccionar desde una URL a otra";s:2:"fr";s:34:"Redirection d''une URL à un autre.";s:2:"it";s:35:"Reindirizza da una URL ad un altra.";s:2:"ru";s:78:"Перенаправления с одного адреса на другой.";s:2:"ar";s:47:"التوجيه من رابط URL إلى آخر.";s:2:"br";s:39:"Redirecionamento de uma URL para outra.";s:2:"cs";s:43:"Přesměrujte z jedné adresy URL na jinou.";s:2:"fi";s:45:"Uudelleenohjaa käyttäjän paikasta toiseen.";s:2:"el";s:81:"Ανακατευθείνετε μια διεύθυνση URL σε μια άλλη";s:2:"he";s:43:"הפניות מכתובת אחת לאחרת";s:2:"lt";s:56:"Peradresuokite puslapį iš vieno adreso (URL) į kitą.";s:2:"da";s:35:"Omadresser fra en URL til en anden.";s:2:"zh";s:33:"將網址轉址、重新定向。";}', 0, 0, 1, 'utilities', 1, 1, 1, 1327041147),
(13, 'a:18:{s:2:"sl";s:10:"Nastavitve";s:2:"en";s:8:"Settings";s:2:"nl";s:12:"Instellingen";s:2:"es";s:15:"Configuraciones";s:2:"fr";s:11:"Paramètres";s:2:"de";s:13:"Einstellungen";s:2:"pl";s:10:"Ustawienia";s:2:"br";s:15:"Configurações";s:2:"zh";s:12:"網站設定";s:2:"it";s:12:"Impostazioni";s:2:"ru";s:18:"Настройки";s:2:"cs";s:10:"Nastavení";s:2:"ar";s:18:"الإعدادات";s:2:"fi";s:9:"Asetukset";s:2:"el";s:18:"Ρυθμίσεις";s:2:"he";s:12:"הגדרות";s:2:"lt";s:10:"Nustatymai";s:2:"da";s:13:"Indstillinger";}', 'settings', '0.6', NULL, 'a:18:{s:2:"sl";s:98:"Dovoljuje administratorjem posodobitev nastavitev kot je Ime strani, sporočil, email naslova itd.";s:2:"en";s:89:"Allows administrators to update settings like Site Name, messages and email address, etc.";s:2:"nl";s:114:"Maakt het administratoren en medewerkers mogelijk om websiteinstellingen zoals naam en beschrijving te veranderen.";s:2:"es";s:131:"Permite a los administradores y al personal configurar los detalles del sitio como el nombre del sitio y la descripción del mismo.";s:2:"fr";s:105:"Permet aux admistrateurs et au personnel de modifier les paramètres du site : nom du site et description";s:2:"de";s:92:"Erlaubt es Administratoren die Einstellungen der Seite wie Name und Beschreibung zu ändern.";s:2:"pl";s:103:"Umożliwia administratorom zmianę ustawień strony jak nazwa strony, opis, e-mail administratora, itd.";s:2:"br";s:120:"Permite com que administradores e a equipe consigam trocar as configurações do website incluindo o nome e descrição.";s:2:"zh";s:99:"網站管理者可更新的重要網站設定。例如：網站名稱、訊息、電子郵件等。";s:2:"it";s:109:"Permette agli amministratori di aggiornare impostazioni quali Nome del Sito, messaggi e indirizzo email, etc.";s:2:"ru";s:135:"Управление настройками сайта - Имя сайта, сообщения, почтовые адреса и т.п.";s:2:"cs";s:102:"Umožňuje administrátorům měnit nastavení webu jako jeho jméno, zprávy a emailovou adresu apod.";s:2:"ar";s:161:"تمكن المدراء من تحديث الإعدادات كإسم الموقع، والرسائل وعناوين البريد الإلكتروني، .. إلخ.";s:2:"fi";s:105:"Mahdollistaa sivuston asetusten muokkaamisen, kuten sivuston nimen, viestit ja sähköpostiosoitteet yms.";s:2:"el";s:230:"Επιτρέπει στους διαχειριστές να τροποποιήσουν ρυθμίσεις όπως το Όνομα του Ιστοτόπου, τα μηνύματα και τις διευθύνσεις email, κ.α.";s:2:"he";s:116:"ניהול הגדרות שונות של האתר כגון: שם האתר, הודעות, כתובות דואר וכו";s:2:"lt";s:104:"Leidžia administratoriams keisti puslapio vavadinimą, žinutes, administratoriaus el. pašta ir kitą.";s:2:"da";s:90:"Lader administratorer opdatere indstillinger som sidenavn, beskeder og email adresse, etc.";}', 1, 0, 1, '0', 1, 1, 1, 1327041147),
(14, 'a:13:{s:2:"en";s:7:"Sitemap";s:2:"el";s:31:"Χάρτης Ιστότοπου";s:2:"de";s:7:"Sitemap";s:2:"nl";s:7:"Sitemap";s:2:"fr";s:12:"Plan du site";s:2:"zh";s:12:"網站地圖";s:2:"it";s:14:"Mappa del sito";s:2:"ru";s:21:"Карта сайта";s:2:"ar";s:23:"خريطة الموقع";s:2:"br";s:12:"Mapa do Site";s:2:"es";s:14:"Mapa del Sitio";s:2:"fi";s:10:"Sivukartta";s:2:"lt";s:16:"Svetainės medis";}', 'sitemap', '1.2', NULL, 'a:14:{s:2:"en";s:87:"The sitemap module creates an index of all pages and an XML sitemap for search engines.";s:2:"el";s:190:"Δημιουργεί έναν κατάλογο όλων των σελίδων και έναν χάρτη σελίδων σε μορφή XML για τις μηχανές αναζήτησης.";s:2:"de";s:92:"Die Sitemap Modul erstellt einen Index aller Seiten und eine XML-Sitemap für Suchmaschinen.";s:2:"nl";s:89:"De sitemap module maakt een index van alle pagina''s en een XML sitemap voor zoekmachines.";s:2:"fr";s:106:"Le module sitemap crée un index de toutes les pages et un plan de site XML pour les moteurs de recherche.";s:2:"zh";s:84:"站點地圖模塊創建一個索引的所有網頁和XML網站地圖搜索引擎。";s:2:"it";s:104:"Il modulo mappa del sito crea un indice di tutte le pagine e una sitemap in XML per i motori di ricerca.";s:2:"ru";s:144:"Карта модуль создает индекс всех страниц и карта сайта XML для поисковых систем.";s:2:"ar";s:120:"وحدة خريطة الموقع تنشئ فهرساً لجميع الصفحات وملف XML لمحركات البحث.";s:2:"br";s:102:"O módulo de mapa do site cria um índice de todas as páginas e um sitemap XML para motores de busca.";s:2:"es";s:111:"El módulo de mapa crea un índice de todas las páginas y un mapa del sitio XML para los motores de búsqueda.";s:2:"fi";s:82:"sivukartta moduuli luo hakemisto kaikista sivuista ja XML sivukartta hakukoneille.";s:2:"lt";s:86:"struktūra modulis sukuria visų puslapių ir XML Sitemap paieškos sistemų indeksas.";s:2:"da";s:86:"Sitemapmodulet opretter et indeks over alle sider og et XML sitemap til søgemaskiner.";}', 0, 1, 0, 'content', 1, 1, 1, 1327041147),
(15, 'a:13:{s:2:"sl";s:14:"Email predloge";s:2:"en";s:15:"Email Templates";s:2:"fr";s:17:"Modèles d''emails";s:2:"nl";s:15:"Email sjablonen";s:2:"es";s:19:"Plantillas de email";s:2:"ar";s:48:"قوالب الرسائل الإلكترونية";s:2:"br";s:17:"Modelos de e-mail";s:2:"el";s:22:"Δυναμικά email";s:2:"he";s:12:"תבניות";s:2:"lt";s:22:"El. laiškų šablonai";s:2:"ru";s:25:"Шаблоны почты";s:2:"da";s:16:"Email skabeloner";s:2:"zh";s:12:"郵件範本";}', 'templates', '0.1', NULL, 'a:13:{s:2:"sl";s:52:"Ustvari, uredi in shrani spremenljive email predloge";s:2:"en";s:46:"Create, edit, and save dynamic email templates";s:2:"fr";s:61:"Créer, éditer et sauver dynamiquement des modèles d''emails";s:2:"nl";s:49:"Maak, bewerk, en beheer dynamische emailsjablonen";s:2:"es";s:54:"Crear, editar y guardar plantillas de email dinámicas";s:2:"ar";s:97:"أنشئ، عدّل واحفظ قوالب البريد الإلكترني الديناميكية.";s:2:"br";s:51:"Criar, editar e salvar modelos de e-mail dinâmicos";s:2:"el";s:108:"Δημιουργήστε, επεξεργαστείτε και αποθηκεύστε δυναμικά email.";s:2:"he";s:54:"ניהול של תבניות דואר אלקטרוני";s:2:"lt";s:58:"Kurk, tvarkyk ir saugok dinaminius el. laiškų šablonus.";s:2:"ru";s:127:"Создавайте, редактируйте и сохраняйте динамические почтовые шаблоны";s:2:"da";s:49:"Opret, redigér og gem dynamiske emailskabeloner.";s:2:"zh";s:61:"新增、編輯與儲存可顯示動態資料的 email 範本";}', 0, 0, 1, 'design', 1, 1, 1, 1327041147),
(16, 'a:18:{s:2:"sl";s:8:"Predloge";s:2:"en";s:6:"Themes";s:2:"nl";s:12:"Thema&apos;s";s:2:"es";s:5:"Temas";s:2:"fr";s:7:"Thèmes";s:2:"de";s:6:"Themen";s:2:"pl";s:6:"Motywy";s:2:"br";s:5:"Temas";s:2:"zh";s:12:"佈景主題";s:2:"it";s:4:"Temi";s:2:"ru";s:8:"Темы";s:2:"ar";s:14:"السّمات";s:2:"cs";s:14:"Motivy vzhledu";s:2:"fi";s:6:"Teemat";s:2:"el";s:31:"Θέματα Εμφάνισης";s:2:"he";s:23:"ערכות נושאים";s:2:"lt";s:5:"Temos";s:2:"da";s:6:"Temaer";}', 'themes', '1.0', NULL, 'a:18:{s:2:"sl";s:133:"Dovoljuje adminom in osebju spremembo izgleda spletne strani, namestitev novega izgleda in urejanja le tega v bolj vizualnem pristopu";s:2:"en";s:86:"Allows admins and staff to switch themes, upload new themes, and manage theme options.";s:2:"nl";s:153:"Maakt het voor administratoren en medewerkers mogelijk om het thema van de website te wijzigen, nieuwe thema&apos;s te uploaden en ze visueel te beheren.";s:2:"es";s:132:"Permite a los administradores y miembros del personal cambiar el tema del sitio web, subir nuevos temas y manejar los ya existentes.";s:2:"fr";s:144:"Permet aux administrateurs et au personnel de modifier le thème du site, de charger de nouveaux thèmes et de le gérer de façon plus visuelle";s:2:"de";s:121:"Ermöglicht es dem Administrator das Seiten Thema auszuwählen, neue Themen hochzulanden oder diese visuell zu verwalten.";s:2:"pl";s:100:"Umożliwia administratorowi zmianę motywu strony, wgrywanie nowych motywów oraz zarządzanie nimi.";s:2:"br";s:125:"Permite aos administradores e membros da equipe fazer upload de novos temas e gerenciá-los através de uma interface visual.";s:2:"zh";s:108:"讓管理者可以更改網站顯示風貌，以視覺化的操作上傳並管理這些網站佈景主題。";s:2:"it";s:120:"Permette ad amministratori e staff di cambiare il tema del sito, carica nuovi temi e gestiscili in um modo più visuale.";s:2:"ru";s:102:"Управление темами оформления сайта, загрузка новых тем.";s:2:"ar";s:170:"تمكّن الإدارة وأعضاء الموقع تغيير سِمة الموقع، وتحميل سمات جديدة وإدارتها بطريقة مرئية سلسة.";s:2:"cs";s:106:"Umožňuje administrátorům a dalším osobám měnit vzhled webu, nahrávat nové motivy a spravovat je.";s:2:"fi";s:129:"Mahdollistaa sivuston teeman vaihtamisen, uusien teemojen lataamisen ja niiden hallinnoinnin visuaalisella käyttöliittymällä.";s:2:"el";s:222:"Επιτρέπει στους διαχειριστές να αλλάξουν το θέμα προβολής του ιστοτόπου να ανεβάσουν νέα θέματα και να τα διαχειριστούν.";s:2:"he";s:63:"ניהול של ערכות נושאים שונות - עיצוב";s:2:"lt";s:105:"Leidžiama administratoriams ir personalui keisti puslapio temą, įkraunant naują temą ir valdyti ją.";s:2:"da";s:108:"Lader administratore ændre websidens tema, uploade nye temaer og håndtére dem med en mere visual tilgang.";}', 0, 0, 1, 'design', 1, 1, 1, 1327041147),
(17, 'a:18:{s:2:"en";s:5:"Users";s:2:"sl";s:10:"Uporabniki";s:2:"nl";s:10:"Gebruikers";s:2:"pl";s:12:"Użytkownicy";s:2:"es";s:8:"Usuarios";s:2:"fr";s:12:"Utilisateurs";s:2:"de";s:8:"Benutzer";s:2:"br";s:9:"Usuários";s:2:"zh";s:6:"用戶";s:2:"it";s:6:"Utenti";s:2:"ru";s:24:"Пользователи";s:2:"ar";s:20:"المستخدمون";s:2:"cs";s:11:"Uživatelé";s:2:"fi";s:12:"Käyttäjät";s:2:"el";s:14:"Χρήστες";s:2:"he";s:14:"משתמשים";s:2:"lt";s:10:"Vartotojai";s:2:"da";s:7:"Brugere";}', 'users', '0.8', NULL, 'a:18:{s:2:"en";s:81:"Let users register and log in to the site, and manage them via the control panel.";s:2:"sl";s:96:"Dovoli uporabnikom za registracijo in prijavo na strani, urejanje le teh preko nadzorne plošče";s:2:"nl";s:88:"Laat gebruikers registreren en inloggen op de site, en beheer ze via het controlepaneel.";s:2:"pl";s:87:"Pozwól użytkownikom na logowanie się na stronie i zarządzaj nimi za pomocą panelu.";s:2:"es";s:138:"Permite el registro de nuevos usuarios quienes podrán loguearse en el sitio. Estos podrán controlarse desde el panel de administración.";s:2:"fr";s:112:"Permet aux utilisateurs de s''enregistrer et de se connecter au site et de les gérer via le panneau de contrôle";s:2:"de";s:108:"Erlaube Benutzern das Registrieren und Einloggen auf der Seite und verwalte sie über die Admin-Oberfläche.";s:2:"br";s:125:"Permite com que usuários se registrem e entrem no site e também que eles sejam gerenciáveis apartir do painel de controle.";s:2:"zh";s:87:"讓用戶可以註冊並登入網站，並且管理者可在控制台內進行管理。";s:2:"it";s:95:"Fai iscrivere de entrare nel sito gli utenti, e gestiscili attraverso il pannello di controllo.";s:2:"ru";s:155:"Управление зарегистрированными пользователями, активирование новых пользователей.";s:2:"ar";s:133:"تمكين المستخدمين من التسجيل والدخول إلى الموقع، وإدارتهم من لوحة التحكم.";s:2:"cs";s:103:"Umožňuje uživatelům se registrovat a přihlašovat a zároveň jejich správu v Kontrolním panelu.";s:2:"fi";s:126:"Antaa käyttäjien rekisteröityä ja kirjautua sisään sivustolle sekä mahdollistaa niiden muokkaamisen hallintapaneelista.";s:2:"el";s:208:"Παρέχει λειτουργίες εγγραφής και σύνδεσης στους επισκέπτες. Επίσης από εδώ γίνεται η διαχείριση των λογαριασμών.";s:2:"he";s:62:"ניהול משתמשים: רישום, הפעלה ומחיקה";s:2:"lt";s:106:"Leidžia vartotojams registruotis ir prisijungti prie puslapio, ir valdyti juos per administravimo panele.";s:2:"da";s:89:"Lader brugere registrere sig og logge ind på sitet, og håndtér dem via kontrolpanelet.";}', 0, 0, 1, '0', 1, 1, 1, 1327041147),
(18, 'a:18:{s:2:"sl";s:13:"Spremenljivke";s:2:"en";s:9:"Variables";s:2:"nl";s:10:"Variabelen";s:2:"pl";s:7:"Zmienne";s:2:"es";s:9:"Variables";s:2:"fr";s:9:"Variables";s:2:"de";s:9:"Variablen";s:2:"br";s:10:"Variáveis";s:2:"zh";s:12:"系統變數";s:2:"it";s:9:"Variabili";s:2:"ru";s:20:"Переменные";s:2:"ar";s:20:"المتغيّرات";s:2:"cs";s:10:"Proměnné";s:2:"fi";s:9:"Muuttujat";s:2:"el";s:20:"Μεταβλητές";s:2:"he";s:12:"משתנים";s:2:"lt";s:10:"Kintamieji";s:2:"da";s:8:"Variable";}', 'variables', '0.4', NULL, 'a:18:{s:2:"sl";s:53:"Urejanje globalnih spremenljivk za dostop od kjerkoli";s:2:"en";s:59:"Manage global variables that can be accessed from anywhere.";s:2:"nl";s:54:"Beheer globale variabelen die overal beschikbaar zijn.";s:2:"pl";s:86:"Zarządzaj globalnymi zmiennymi do których masz dostęp z każdego miejsca aplikacji.";s:2:"es";s:50:"Manage global variables to access from everywhere.";s:2:"fr";s:50:"Manage global variables to access from everywhere.";s:2:"de";s:74:"Verwaltet globale Variablen, auf die von überall zugegriffen werden kann.";s:2:"br";s:61:"Gerencia as variáveis globais acessíveis de qualquer lugar.";s:2:"zh";s:45:"管理此網站內可存取的全局變數。";s:2:"it";s:58:"Gestisci le variabili globali per accedervi da ogni parte.";s:2:"ru";s:136:"Управление глобальными переменными, которые доступны в любом месте сайта.";s:2:"ar";s:97:"إدارة المُتغيّرات العامة لاستخدامها في أرجاء الموقع.";s:2:"cs";s:56:"Spravujte globální proměnné přístupné odkudkoliv.";s:2:"fi";s:66:"Hallitse globaali muuttujia, joihin pääsee käsiksi mistä vain.";s:2:"el";s:129:"Διαχείριση μεταβλητών που είναι προσβάσιμες από παντού στον ιστότοπο.";s:2:"he";s:96:"ניהול משתנים גלובליים אשר ניתנים להמרה בכל חלקי האתר";s:2:"lt";s:64:"Globalių kintamujų tvarkymas kurie yra pasiekiami iš bet kur.";s:2:"da";s:51:"Håndtér globale variable som kan tilgås overalt.";}', 0, 0, 1, 'content', 1, 1, 1, 1327041147),
(19, 'a:14:{s:2:"sl";s:9:"Vtičniki";s:2:"en";s:7:"Widgets";s:2:"es";s:7:"Widgets";s:2:"br";s:7:"Widgets";s:2:"de";s:7:"Widgets";s:2:"nl";s:7:"Widgets";s:2:"fr";s:7:"Widgets";s:2:"zh";s:9:"小組件";s:2:"it";s:7:"Widgets";s:2:"ru";s:14:"Виджеты";s:2:"ar";s:12:"الودجت";s:2:"cs";s:7:"Widgety";s:2:"fi";s:8:"Widgetit";s:2:"lt";s:11:"Papildiniai";}', 'widgets', '1.1', NULL, 'a:16:{s:2:"sl";s:61:"Urejanje manjših delov blokov strani ti. Vtičniki (Widgets)";s:2:"en";s:69:"Manage small sections of self-contained logic in blocks or "Widgets".";s:2:"es";s:75:"Manejar pequeñas secciones de lógica autocontenida en bloques o "Widgets"";s:2:"br";s:77:"Gerenciar pequenas seções de conteúdos em bloco conhecidos como "Widgets".";s:2:"de";s:62:"Verwaltet kleine, eigentständige Bereiche, genannt "Widgets".";s:2:"nl";s:75:"Beheer kleine onderdelen die zelfstandige logica bevatten, ofwel "Widgets".";s:2:"fr";s:41:"Gérer des mini application ou "Widgets".";s:2:"zh";s:103:"可將小段的程式碼透過小組件來管理。即所謂 "Widgets"，或稱為小工具、部件。";s:2:"it";s:70:"Gestisci piccole sezioni di logica a se stante in blocchi o "Widgets".";s:2:"ru";s:91:"Управление небольшими, самостоятельными блоками.";s:2:"ar";s:138:"إدارة أقسام صغيرة من البرمجيات في مساحات الموقع أو ما يُسمّى بالـ"وِدْجِتْ".";s:2:"cs";s:56:"Spravujte malé funkční části webu neboli "Widgety".";s:2:"fi";s:83:"Hallitse pieniä osioita, jotka sisältävät erillisiä lohkoja tai "Widgettejä".";s:2:"el";s:149:"Διαχείριση μικρών τμημάτων αυτόνομης προγραμματιστικής λογικής σε πεδία ή "Widgets".";s:2:"lt";s:43:"Nedidelių, savarankiškų blokų valdymas.";s:2:"da";s:74:"Håndter små sektioner af selv-opretholdt logik i blokke eller "Widgets".";}', 0, 0, 1, 'content', 1, 1, 1, 1327041147),
(26, 'a:17:{s:2:"sl";s:7:"Skupine";s:2:"en";s:15:"Family Planning";s:2:"br";s:6:"Grupos";s:2:"de";s:7:"Gruppen";s:2:"nl";s:7:"Groepen";s:2:"fr";s:7:"Groupes";s:2:"zh";s:6:"群組";s:2:"it";s:6:"Gruppi";s:2:"ru";s:12:"Группы";s:2:"ar";s:18:"المجموعات";s:2:"cs";s:7:"Skupiny";s:2:"es";s:6:"Grupos";s:2:"fi";s:7:"Ryhmät";s:2:"el";s:12:"Ομάδες";s:2:"he";s:12:"קבוצות";s:2:"lt";s:7:"Grupės";s:2:"da";s:7:"Grupper";}', 'family', '1.0', NULL, 'a:17:{s:2:"sl";s:64:"Uporabniki so lahko razvrščeni v skupine za urejanje dovoljenj";s:2:"en";s:23:"Manage Family Planning.";s:2:"br";s:72:"Usuários podem ser inseridos em grupos para gerenciar suas permissões.";s:2:"de";s:85:"Benutzer können zu Gruppen zusammengefasst werden um diesen Zugriffsrechte zu geben.";s:2:"nl";s:73:"Gebruikers kunnen in groepen geplaatst worden om rechten te kunnen geven.";s:2:"fr";s:82:"Les utilisateurs peuvent appartenir à des groupes afin de gérer les permissions.";s:2:"zh";s:45:"用戶可以依群組分類並管理其權限";s:2:"it";s:69:"Gli utenti possono essere inseriti in gruppi per gestirne i permessi.";s:2:"ru";s:134:"Пользователей можно объединять в группы, для управления правами доступа.";s:2:"ar";s:100:"يمكن وضع المستخدمين في مجموعات لتسهيل إدارة صلاحياتهم.";s:2:"cs";s:77:"Uživatelé mohou být rozřazeni do skupin pro lepší správu oprávnění.";s:2:"es";s:75:"Los usuarios podrán ser colocados en grupos para administrar sus permisos.";s:2:"fi";s:84:"Käyttäjät voidaan liittää ryhmiin, jotta käyttöoikeuksia voidaan hallinnoida.";s:2:"el";s:159:"Οι χρήστες μπορούν να τοποθετηθούν σε ομάδες και να διαχειριστείτε τα δικαιώματά τους.";s:2:"he";s:62:"נותן אפשרות לאסוף משתמשים לקבוצות";s:2:"lt";s:67:"Vartotojai gali būti priskirti grupei tam, kad valdyti jų teises.";s:2:"da";s:49:"Brugere kan inddeles i grupper for adgangskontrol";}', 0, 0, 1, 'clients', 1, 1, 0, 1332010033),
(25, 'a:17:{s:2:"sl";s:7:"Skupine";s:2:"en";s:7:"Clients";s:2:"br";s:6:"Grupos";s:2:"de";s:7:"Gruppen";s:2:"nl";s:7:"Groepen";s:2:"fr";s:7:"Groupes";s:2:"zh";s:6:"群組";s:2:"it";s:6:"Gruppi";s:2:"ru";s:12:"Группы";s:2:"ar";s:18:"المجموعات";s:2:"cs";s:7:"Skupiny";s:2:"es";s:6:"Grupos";s:2:"fi";s:7:"Ryhmät";s:2:"el";s:12:"Ομάδες";s:2:"he";s:12:"קבוצות";s:2:"lt";s:7:"Grupės";s:2:"da";s:7:"Grupper";}', 'clients', '1.0', NULL, 'a:17:{s:2:"sl";s:64:"Uporabniki so lahko razvrščeni v skupine za urejanje dovoljenj";s:2:"en";s:15:"Manage Clients.";s:2:"br";s:72:"Usuários podem ser inseridos em grupos para gerenciar suas permissões.";s:2:"de";s:85:"Benutzer können zu Gruppen zusammengefasst werden um diesen Zugriffsrechte zu geben.";s:2:"nl";s:73:"Gebruikers kunnen in groepen geplaatst worden om rechten te kunnen geven.";s:2:"fr";s:82:"Les utilisateurs peuvent appartenir à des groupes afin de gérer les permissions.";s:2:"zh";s:45:"用戶可以依群組分類並管理其權限";s:2:"it";s:69:"Gli utenti possono essere inseriti in gruppi per gestirne i permessi.";s:2:"ru";s:134:"Пользователей можно объединять в группы, для управления правами доступа.";s:2:"ar";s:100:"يمكن وضع المستخدمين في مجموعات لتسهيل إدارة صلاحياتهم.";s:2:"cs";s:77:"Uživatelé mohou být rozřazeni do skupin pro lepší správu oprávnění.";s:2:"es";s:75:"Los usuarios podrán ser colocados en grupos para administrar sus permisos.";s:2:"fi";s:84:"Käyttäjät voidaan liittää ryhmiin, jotta käyttöoikeuksia voidaan hallinnoida.";s:2:"el";s:159:"Οι χρήστες μπορούν να τοποθετηθούν σε ομάδες και να διαχειριστείτε τα δικαιώματά τους.";s:2:"he";s:62:"נותן אפשרות לאסוף משתמשים לקבוצות";s:2:"lt";s:67:"Vartotojai gali būti priskirti grupei tam, kad valdyti jų teises.";s:2:"da";s:49:"Brugere kan inddeles i grupper for adgangskontrol";}', 0, 0, 1, 'clients', 1, 1, 0, 1327831784),
(27, 'a:17:{s:2:"sl";s:7:"Skupine";s:2:"en";s:13:"Prenatal Care";s:2:"br";s:6:"Grupos";s:2:"de";s:7:"Gruppen";s:2:"nl";s:7:"Groepen";s:2:"fr";s:7:"Groupes";s:2:"zh";s:6:"群組";s:2:"it";s:6:"Gruppi";s:2:"ru";s:12:"Группы";s:2:"ar";s:18:"المجموعات";s:2:"cs";s:7:"Skupiny";s:2:"es";s:6:"Grupos";s:2:"fi";s:7:"Ryhmät";s:2:"el";s:12:"Ομάδες";s:2:"he";s:12:"קבוצות";s:2:"lt";s:7:"Grupės";s:2:"da";s:7:"Grupper";}', 'prenatal', '1.0', NULL, 'a:17:{s:2:"sl";s:64:"Uporabniki so lahko razvrščeni v skupine za urejanje dovoljenj";s:2:"en";s:15:"Manage Clients.";s:2:"br";s:72:"Usuários podem ser inseridos em grupos para gerenciar suas permissões.";s:2:"de";s:85:"Benutzer können zu Gruppen zusammengefasst werden um diesen Zugriffsrechte zu geben.";s:2:"nl";s:73:"Gebruikers kunnen in groepen geplaatst worden om rechten te kunnen geven.";s:2:"fr";s:82:"Les utilisateurs peuvent appartenir à des groupes afin de gérer les permissions.";s:2:"zh";s:45:"用戶可以依群組分類並管理其權限";s:2:"it";s:69:"Gli utenti possono essere inseriti in gruppi per gestirne i permessi.";s:2:"ru";s:134:"Пользователей можно объединять в группы, для управления правами доступа.";s:2:"ar";s:100:"يمكن وضع المستخدمين في مجموعات لتسهيل إدارة صلاحياتهم.";s:2:"cs";s:77:"Uživatelé mohou být rozřazeni do skupin pro lepší správu oprávnění.";s:2:"es";s:75:"Los usuarios podrán ser colocados en grupos para administrar sus permisos.";s:2:"fi";s:84:"Käyttäjät voidaan liittää ryhmiin, jotta käyttöoikeuksia voidaan hallinnoida.";s:2:"el";s:159:"Οι χρήστες μπορούν να τοποθετηθούν σε ομάδες και να διαχειριστείτε τα δικαιώματά τους.";s:2:"he";s:62:"נותן אפשרות לאסוף משתמשים לקבוצות";s:2:"lt";s:67:"Vartotojai gali būti priskirti grupei tam, kad valdyti jų teises.";s:2:"da";s:49:"Brugere kan inddeles i grupper for adgangskontrol";}', 0, 0, 1, 'clients', 1, 1, 0, 1328208561);
INSERT INTO `default_modules` (`id`, `name`, `slug`, `version`, `type`, `description`, `skip_xss`, `is_frontend`, `is_backend`, `menu`, `enabled`, `installed`, `is_core`, `updated_on`) VALUES
(28, 'a:17:{s:2:"sl";s:7:"Skupine";s:2:"en";s:10:"Child Care";s:2:"br";s:6:"Grupos";s:2:"de";s:7:"Gruppen";s:2:"nl";s:7:"Groepen";s:2:"fr";s:7:"Groupes";s:2:"zh";s:6:"群組";s:2:"it";s:6:"Gruppi";s:2:"ru";s:12:"Группы";s:2:"ar";s:18:"المجموعات";s:2:"cs";s:7:"Skupiny";s:2:"es";s:6:"Grupos";s:2:"fi";s:7:"Ryhmät";s:2:"el";s:12:"Ομάδες";s:2:"he";s:12:"קבוצות";s:2:"lt";s:7:"Grupės";s:2:"da";s:7:"Grupper";}', 'childcare', '1.0', NULL, 'a:17:{s:2:"sl";s:64:"Uporabniki so lahko razvrščeni v skupine za urejanje dovoljenj";s:2:"en";s:15:"Manage Clients.";s:2:"br";s:72:"Usuários podem ser inseridos em grupos para gerenciar suas permissões.";s:2:"de";s:85:"Benutzer können zu Gruppen zusammengefasst werden um diesen Zugriffsrechte zu geben.";s:2:"nl";s:73:"Gebruikers kunnen in groepen geplaatst worden om rechten te kunnen geven.";s:2:"fr";s:82:"Les utilisateurs peuvent appartenir à des groupes afin de gérer les permissions.";s:2:"zh";s:45:"用戶可以依群組分類並管理其權限";s:2:"it";s:69:"Gli utenti possono essere inseriti in gruppi per gestirne i permessi.";s:2:"ru";s:134:"Пользователей можно объединять в группы, для управления правами доступа.";s:2:"ar";s:100:"يمكن وضع المستخدمين في مجموعات لتسهيل إدارة صلاحياتهم.";s:2:"cs";s:77:"Uživatelé mohou být rozřazeni do skupin pro lepší správu oprávnění.";s:2:"es";s:75:"Los usuarios podrán ser colocados en grupos para administrar sus permisos.";s:2:"fi";s:84:"Käyttäjät voidaan liittää ryhmiin, jotta käyttöoikeuksia voidaan hallinnoida.";s:2:"el";s:159:"Οι χρήστες μπορούν να τοποθετηθούν σε ομάδες και να διαχειριστείτε τα δικαιώματά τους.";s:2:"he";s:62:"נותן אפשרות לאסוף משתמשים לקבוצות";s:2:"lt";s:67:"Vartotojai gali būti priskirti grupei tam, kad valdyti jų teises.";s:2:"da";s:49:"Brugere kan inddeles i grupper for adgangskontrol";}', 0, 0, 1, 'clients', 1, 1, 0, 1328207209),
(29, 'a:1:{s:2:"en";s:10:"Postpartum";}', 'postpartum', '1.0', NULL, 'a:1:{s:2:"en";s:25:"Manage Postpartum Clients";}', 0, 0, 1, 'clients', 1, 1, 0, 1332010033),
(36, 'a:17:{s:2:"sl";s:7:"Skupine";s:2:"en";s:13:"Prenatal Care";s:2:"br";s:6:"Grupos";s:2:"de";s:7:"Gruppen";s:2:"nl";s:7:"Groepen";s:2:"fr";s:7:"Groupes";s:2:"zh";s:6:"群組";s:2:"it";s:6:"Gruppi";s:2:"ru";s:12:"Группы";s:2:"ar";s:18:"المجموعات";s:2:"cs";s:7:"Skupiny";s:2:"es";s:6:"Grupos";s:2:"fi";s:7:"Ryhmät";s:2:"el";s:12:"Ομάδες";s:2:"he";s:12:"קבוצות";s:2:"lt";s:7:"Grupės";s:2:"da";s:7:"Grupper";}', 'prenatals', '1.0', NULL, 'a:17:{s:2:"sl";s:64:"Uporabniki so lahko razvrščeni v skupine za urejanje dovoljenj";s:2:"en";s:23:"Manage Prenatal Clients";s:2:"br";s:72:"Usuários podem ser inseridos em grupos para gerenciar suas permissões.";s:2:"de";s:85:"Benutzer können zu Gruppen zusammengefasst werden um diesen Zugriffsrechte zu geben.";s:2:"nl";s:73:"Gebruikers kunnen in groepen geplaatst worden om rechten te kunnen geven.";s:2:"fr";s:82:"Les utilisateurs peuvent appartenir à des groupes afin de gérer les permissions.";s:2:"zh";s:45:"用戶可以依群組分類並管理其權限";s:2:"it";s:69:"Gli utenti possono essere inseriti in gruppi per gestirne i permessi.";s:2:"ru";s:134:"Пользователей можно объединять в группы, для управления правами доступа.";s:2:"ar";s:100:"يمكن وضع المستخدمين في مجموعات لتسهيل إدارة صلاحياتهم.";s:2:"cs";s:77:"Uživatelé mohou být rozřazeni do skupin pro lepší správu oprávnění.";s:2:"es";s:75:"Los usuarios podrán ser colocados en grupos para administrar sus permisos.";s:2:"fi";s:84:"Käyttäjät voidaan liittää ryhmiin, jotta käyttöoikeuksia voidaan hallinnoida.";s:2:"el";s:159:"Οι χρήστες μπορούν να τοποθετηθούν σε ομάδες και να διαχειριστείτε τα δικαιώματά τους.";s:2:"he";s:62:"נותן אפשרות לאסוף משתמשים לקבוצות";s:2:"lt";s:67:"Vartotojai gali būti priskirti grupei tam, kad valdyti jų teises.";s:2:"da";s:49:"Brugere kan inddeles i grupper for adgangskontrol";}', 0, 0, 1, 'clients', 1, 1, 0, 1332055966),
(31, 'a:1:{s:2:"en";s:13:"Sick Children";}', 'sick_children', '1.0', NULL, 'a:1:{s:2:"en";s:20:"Manage Sick Children";}', 0, 0, 1, 'clients', 1, 1, 0, 1332010033),
(33, 'a:1:{s:2:"en";s:20:"Environmental Health";}', 'environmental_health', '1.0', NULL, 'a:1:{s:2:"en";s:35:"Manage Environmental Health Clients";}', 0, 0, 1, 'clients', 1, 1, 0, 1332104029),
(32, 'a:1:{s:2:"en";s:25:"Children Under 1 Year Old";}', 'children_under_one', '1.0', NULL, 'a:1:{s:2:"en";s:33:"Manage Children Under 1 Year Old.";}', 0, 0, 1, 'clients', 1, 1, 0, 1329720551),
(37, 'a:1:{s:2:"en";s:13:"Dental Health";}', 'dental_health', '1.0', NULL, 'a:1:{s:2:"en";s:28:"Manage Dental Health Clients";}', 0, 0, 1, 'clients', 1, 1, 0, 1337113532),
(40, 'a:1:{s:2:"en";s:8:"Diagnose";}', 'diagnose', '1.0', NULL, 'a:1:{s:2:"en";s:21:"Manage Client Visits.";}', 0, 0, 1, 'clients', 1, 1, 0, 1337140174),
(41, 'a:1:{s:2:"en";s:6:"Visits";}', 'visits', '1.0', NULL, 'a:1:{s:2:"en";s:21:"Manage Client Visits.";}', 0, 0, 1, 'clients', 1, 1, 0, 1337268031),
(42, 'a:1:{s:2:"en";s:13:"Consultations";}', 'consultations', '1.0', NULL, 'a:1:{s:2:"en";s:28:"Manage Client Consultations.";}', 0, 0, 1, 'clients', 1, 1, 0, 1337318657);

-- --------------------------------------------------------

--
-- Table structure for table `default_navigation_groups`
--

CREATE TABLE IF NOT EXISTS `default_navigation_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `abbrev` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Navigation groupings. Eg, header, sidebar, footer, etc' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `default_navigation_groups`
--

INSERT INTO `default_navigation_groups` (`id`, `title`, `abbrev`) VALUES
(1, 'Header', 'header'),
(2, 'Sidebar', 'sidebar'),
(3, 'Footer', 'footer');

-- --------------------------------------------------------

--
-- Table structure for table `default_navigation_links`
--

CREATE TABLE IF NOT EXISTS `default_navigation_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `parent` int(11) NOT NULL DEFAULT '0',
  `link_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'uri',
  `page_id` int(11) NOT NULL DEFAULT '0',
  `module_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `uri` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `navigation_group_id` int(5) NOT NULL DEFAULT '0',
  `position` int(5) NOT NULL DEFAULT '0',
  `target` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `restricted_to` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `class` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `navigation_group_id - normal` (`navigation_group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Links for site navigation' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `default_navigation_links`
--

INSERT INTO `default_navigation_links` (`id`, `title`, `parent`, `link_type`, `page_id`, `module_name`, `url`, `uri`, `navigation_group_id`, `position`, `target`, `restricted_to`, `class`) VALUES
(1, 'Home', 0, 'page', 1, '', '', '', 1, 1, NULL, NULL, ''),
(2, 'Contact', 0, 'page', 3, '', '', '', 1, 2, NULL, NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `default_pages`
--

CREATE TABLE IF NOT EXISTS `default_pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `uri` text COLLATE utf8_unicode_ci,
  `parent_id` int(11) DEFAULT '0',
  `revision_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `layout_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `css` text COLLATE utf8_unicode_ci,
  `js` text COLLATE utf8_unicode_ci,
  `meta_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `meta_keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `meta_description` text COLLATE utf8_unicode_ci,
  `rss_enabled` int(1) NOT NULL DEFAULT '0',
  `comments_enabled` int(1) NOT NULL DEFAULT '0',
  `status` enum('draft','live') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `created_on` int(11) NOT NULL DEFAULT '0',
  `updated_on` int(11) NOT NULL DEFAULT '0',
  `restricted_to` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_home` tinyint(1) NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `slug` (`slug`),
  KEY `parent` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='User Editable Pages' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `default_pages`
--

INSERT INTO `default_pages` (`id`, `slug`, `title`, `uri`, `parent_id`, `revision_id`, `layout_id`, `css`, `js`, `meta_title`, `meta_keywords`, `meta_description`, `rss_enabled`, `comments_enabled`, `status`, `created_on`, `updated_on`, `restricted_to`, `is_home`, `order`) VALUES
(1, 'home', 'Home', 'home', 0, '1', '1', NULL, NULL, '', '', NULL, 0, 0, 'live', 1326243427, 1326243427, '', 1, 0),
(2, '404', 'Page missing', '404', 0, '2', '1', NULL, NULL, '', '', NULL, 0, 0, 'live', 1326243427, 1326243427, '', 0, 0),
(3, 'contact', 'Contact', 'contact', 0, '3', '1', NULL, NULL, '', '', NULL, 0, 0, 'live', 1326243427, 1326243427, '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `default_page_chunks`
--

CREATE TABLE IF NOT EXISTS `default_page_chunks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `page_id` int(11) NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `parsed` text COLLATE utf8_unicode_ci NOT NULL,
  `type` set('html','markdown','wysiwyg-advanced','wysiwyg-simple') COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `default_page_chunks`
--

INSERT INTO `default_page_chunks` (`id`, `slug`, `page_id`, `body`, `parsed`, `type`, `sort`) VALUES
(1, 'default', 1, '<p>Welcome to our homepage. We have not quite finished setting up our website yet, but please add us to your bookmarks and come back soon.</p>', '', 'wysiwyg-advanced', 0),
(2, 'default', 2, '<p>We cannot find the page you are looking for, please click <a title="Home" href="{{ pages:url id=''1'' }}">here</a> to go to the homepage.</p>', '', 'wysiwyg-advanced', 0),
(3, 'default', 3, '<p>To contact us please fill out the form below.</p>\n				{{ contact:form name="text|required" email="text|required|valid_email" subject="dropdown|Support|Sales|Feedback|Other" message="textarea" attachment="file|zip" }}\n					<div><label for="name">Name:</label>{{ name }}</div>\n					<div><label for="email">Email:</label>{{ email }}</div>\n					<div><label for="subject">Subject:</label>{{ subject }}</div>\n					<div><label for="message">Message:</label>{{ message }}</div>\n					<div><label for="attachment">Attach  a zip file:</label>{{ attachment }}</div>\n				{{ /contact:form }}', '', 'wysiwyg-advanced', 0);

-- --------------------------------------------------------

--
-- Table structure for table `default_page_layouts`
--

CREATE TABLE IF NOT EXISTS `default_page_layouts` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `css` text COLLATE utf8_unicode_ci,
  `js` text COLLATE utf8_unicode_ci,
  `theme_layout` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default',
  `updated_on` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Store shared page layouts & CSS' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `default_page_layouts`
--

INSERT INTO `default_page_layouts` (`id`, `title`, `body`, `css`, `js`, `theme_layout`, `updated_on`) VALUES
(1, 'Default', '<h2>{{ page:title }}</h2>\n\n\n{{ page:body }}', '', '', 'default', 1326243427);

-- --------------------------------------------------------

--
-- Table structure for table `default_permissions`
--

CREATE TABLE IF NOT EXISTS `default_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `module` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `roles` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Contains a list of modules that a group can access.' AUTO_INCREMENT=76 ;

--
-- Dumping data for table `default_permissions`
--

INSERT INTO `default_permissions` (`id`, `group_id`, `module`, `roles`) VALUES
(36, 4, 'clients', NULL),
(37, 4, 'dental_health', NULL),
(38, 5, 'clients', NULL),
(39, 5, 'environmental_health', NULL),
(67, 3, 'children_under_one', NULL),
(68, 3, 'clients', NULL),
(69, 3, 'consultations', NULL),
(70, 3, 'dental_health', NULL),
(71, 3, 'environmental_health', NULL),
(72, 3, 'family', NULL),
(73, 3, 'postpartum', NULL),
(74, 3, 'prenatals', NULL),
(75, 3, 'sick_children', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `default_postpartum`
--

CREATE TABLE IF NOT EXISTS `default_postpartum` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `client_id` int(10) NOT NULL,
  `delivery` int(11) NOT NULL,
  `visits_day` int(11) DEFAULT NULL,
  `visits_week` int(11) DEFAULT NULL,
  `breastfeeding` int(11) DEFAULT NULL,
  `iron1_date` int(11) DEFAULT NULL,
  `iron1_tabs` int(4) DEFAULT NULL,
  `iron2_date` int(11) DEFAULT NULL,
  `iron2_tabs` int(4) DEFAULT NULL,
  `iron3_date` int(11) DEFAULT NULL,
  `iron3_tabs` int(4) DEFAULT NULL,
  `vitamin_a` int(11) DEFAULT NULL,
  `remarks` varchar(200) DEFAULT NULL,
  `date_added` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `default_postpartum`
--


-- --------------------------------------------------------

--
-- Table structure for table `default_prenatals`
--

CREATE TABLE IF NOT EXISTS `default_prenatals` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `client_id` int(10) NOT NULL,
  `last_menstrual_period` int(11) DEFAULT NULL,
  `gravida` varchar(5) DEFAULT NULL,
  `para` varchar(5) DEFAULT NULL,
  `term` varchar(5) DEFAULT NULL,
  `abortion` varchar(5) DEFAULT NULL,
  `live` varchar(5) DEFAULT NULL,
  `estimated_date_confinement` int(11) DEFAULT NULL,
  `prenatal_visit1` int(11) DEFAULT NULL,
  `prenatal_visit2` int(11) DEFAULT NULL,
  `prenatal_visit3` int(11) DEFAULT NULL,
  `tetanus_status` varchar(50) DEFAULT NULL,
  `tt1` int(11) DEFAULT NULL,
  `tt2` int(11) DEFAULT NULL,
  `tt3` int(11) DEFAULT NULL,
  `tt4` int(11) DEFAULT NULL,
  `tt5` int(11) DEFAULT NULL,
  `date_given_vit_a` int(11) DEFAULT NULL,
  `iron1_date` int(11) DEFAULT NULL,
  `iron1_number` varchar(5) DEFAULT NULL,
  `iron2_date` int(11) DEFAULT NULL,
  `iron2_number` varchar(5) DEFAULT NULL,
  `iron3_date` int(11) DEFAULT NULL,
  `iron3_number` varchar(5) DEFAULT NULL,
  `iron4_date` int(11) DEFAULT NULL,
  `iron4_number` varchar(5) DEFAULT NULL,
  `iron5_date` int(11) DEFAULT NULL,
  `iron5_number` varchar(5) DEFAULT NULL,
  `iron6_date` int(11) DEFAULT NULL,
  `iron6_number` varchar(5) DEFAULT NULL,
  `risk_code` varchar(5) DEFAULT NULL,
  `risk_date_detected` int(11) DEFAULT NULL,
  `pregnancy_date_terminated` int(11) DEFAULT NULL,
  `pregnancy_outcome` varchar(5) DEFAULT NULL,
  `livebirths_birth_weight` varchar(5) DEFAULT NULL,
  `livebirths_place_delivery` varchar(50) DEFAULT NULL,
  `livebirths_type_delivery` varchar(20) DEFAULT NULL,
  `livebirths_attended_by` varchar(50) DEFAULT NULL,
  `remarks` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `default_prenatals`
--


-- --------------------------------------------------------

--
-- Table structure for table `default_profiles`
--

CREATE TABLE IF NOT EXISTS `default_profiles` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `display_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `company` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `bio` text COLLATE utf8_unicode_ci,
  `dob` int(11) DEFAULT NULL,
  `gender` set('m','f','') COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_line1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_line2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_line3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postcode` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated_on` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Dumping data for table `default_profiles`
--

INSERT INTO `default_profiles` (`id`, `user_id`, `display_name`, `first_name`, `last_name`, `company`, `lang`, `bio`, `dob`, `gender`, `phone`, `mobile`, `address_line1`, `address_line2`, `address_line3`, `postcode`, `website`, `updated_on`) VALUES
(1, 1, 'ronald minoza', 'ronald', 'minoza', '', 'en', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, 'Reagan', 'reagan', 'minoza', NULL, 'en', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 3, 'Bebang', 'Chelsea', 'Minoza', NULL, 'en', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 4, 'Default User', 'My First Name', 'My Last Name', NULL, 'en', '', 1325372400, '', '', '', '', '', '', '', '', 1337405117),
(5, 5, 'Elina', 'Elina', 'Gilbert', NULL, 'en', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 6, 'Maricar', 'Maricar', 'Dulala', NULL, 'en', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 7, 'fervills', 'fernando', 'villamor', NULL, 'en', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 8, 'ofyourlife', 'thehero', 'hasarrive', NULL, 'en', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 9, 'ofyourlife', 'thehero', 'hasarrive', NULL, 'en', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 10, 'villsfer', 'thisis', 'theway', NULL, 'en', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 11, 'utilities', 'content', 'clients', NULL, 'en', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 12, 'thegreen', 'settings', 'add ons', NULL, 'en', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `default_redirects`
--

CREATE TABLE IF NOT EXISTS `default_redirects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `to` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `request` (`from`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `default_redirects`
--


-- --------------------------------------------------------

--
-- Table structure for table `default_referrer`
--

CREATE TABLE IF NOT EXISTS `default_referrer` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `lastname` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `profession` varchar(50) NOT NULL,
  `date_added` int(11) NOT NULL,
  `added_by` int(10) NOT NULL,
  `last_update` int(11) DEFAULT NULL,
  `last_updated_by` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `default_referrer`
--


-- --------------------------------------------------------

--
-- Table structure for table `default_sc`
--

CREATE TABLE IF NOT EXISTS `default_sc` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `client_id` int(10) NOT NULL,
  `vitamin_a` enum('1','0') NOT NULL DEFAULT '0',
  `anemic` enum('1','0') NOT NULL DEFAULT '0',
  `diarrhea` enum('1','0') NOT NULL DEFAULT '0',
  `pneumonia` enum('1','0') NOT NULL DEFAULT '0',
  `remarks` text,
  `date_added` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `default_sc`
--


-- --------------------------------------------------------

--
-- Table structure for table `default_sc_anemic_children_iron`
--

CREATE TABLE IF NOT EXISTS `default_sc_anemic_children_iron` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sc_id` int(10) NOT NULL,
  `anemic_age` int(4) DEFAULT NULL,
  `date_started` int(11) DEFAULT NULL,
  `date_completed` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `default_sc_anemic_children_iron`
--


-- --------------------------------------------------------

--
-- Table structure for table `default_sc_diarrhea_cases`
--

CREATE TABLE IF NOT EXISTS `default_sc_diarrhea_cases` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sc_id` int(10) NOT NULL,
  `diarrhea_age` int(4) DEFAULT NULL,
  `ort` int(11) DEFAULT NULL,
  `ors` int(11) DEFAULT NULL,
  `ors_zinc` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `default_sc_diarrhea_cases`
--


-- --------------------------------------------------------

--
-- Table structure for table `default_sc_pneumonia_cases`
--

CREATE TABLE IF NOT EXISTS `default_sc_pneumonia_cases` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sc_id` int(10) NOT NULL,
  `pneumonia_age` int(4) DEFAULT NULL,
  `date_given_treatment` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `default_sc_pneumonia_cases`
--


-- --------------------------------------------------------

--
-- Table structure for table `default_sc_vitamin_a`
--

CREATE TABLE IF NOT EXISTS `default_sc_vitamin_a` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sc_id` int(10) NOT NULL,
  `six_months` enum('1','0') NOT NULL DEFAULT '0',
  `twelve_months` enum('1','0') NOT NULL DEFAULT '0',
  `sixty_months` enum('1','0') NOT NULL DEFAULT '0',
  `diagnosis` varchar(50) DEFAULT NULL,
  `date_given` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `default_sc_vitamin_a`
--


-- --------------------------------------------------------

--
-- Table structure for table `default_settings`
--

CREATE TABLE IF NOT EXISTS `default_settings` (
  `slug` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `type` set('text','textarea','password','select','select-multiple','radio','checkbox') COLLATE utf8_unicode_ci NOT NULL,
  `default` text COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `options` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_required` tinyint(1) NOT NULL,
  `is_gui` tinyint(1) NOT NULL,
  `module` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `order` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`slug`),
  UNIQUE KEY `unique - slug` (`slug`),
  KEY `index - slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Stores all sorts of settings for the admin to change';

--
-- Dumping data for table `default_settings`
--

INSERT INTO `default_settings` (`slug`, `title`, `description`, `type`, `default`, `value`, `options`, `is_required`, `is_gui`, `module`, `order`) VALUES
('activation_email', 'Activation Email', 'Send out an e-mail when a user signs up with an activation link. Disable this to let only admins activate accounts.', 'radio', '1', '0', '1=Enabled|0=Disabled', 0, 1, 'users', 925),
('addons_upload', 'Addons Upload Permissions', 'Keeps mere admins from uploading addons by default', 'text', '0', '1', '', 1, 0, '', 0),
('admin_force_https', 'Force HTTPS for Control Panel?', 'Allow only the HTTPS protocol when using the Control Panel?', 'radio', '0', '', '1=Yes|0=No', 1, 1, '', 961),
('admin_theme', 'Control Panel Theme', 'Select the theme for the control panel.', '', 'pyrocms', '', 'func:get_themes', 1, 0, '', 0),
('akismet_api_key', 'Akismet API Key', 'Akismet is a spam-blocker from the WordPress team. It keeps spam under control without forcing users to get past human-checking CAPTCHA forms.', 'text', '', '', '', 0, 1, 'integration', 948),
('auto_username', 'Auto Username', 'Create the username automatically, meaning users can skip making one on registration.', 'radio', '1', '0', '1=Enabled|0=Disabled', 0, 1, 'users', 922),
('comment_markdown', 'Allow Markdown', 'Do you want to allow visitors to post comments using Markdown?', 'select', '0', '0', '0=Text Only|1=Allow Markdown', 1, 1, 'comments', 928),
('comment_order', 'Comment Order', 'Sort order in which to display comments.', 'select', 'ASC', 'ASC', 'ASC=Oldest First|DESC=Newest First', 1, 1, 'comments', 931),
('contact_email', 'Contact E-mail', 'All e-mails from users, guests and the site will go to this e-mail address.', 'text', 'ronaldrhey@gmail.com', '', '', 1, 1, 'email', 947),
('currency', 'Currency', 'The currency symbol for use on products, services, etc.', 'text', '&pound;', '', '', 1, 1, '', 976),
('dashboard_rss', 'Dashboard RSS Feed', 'Link to an RSS feed that will be displayed on the dashboard.', 'text', 'http://feeds.feedburner.com/pyrocms-installed', '', '', 0, 1, '', 965),
('dashboard_rss_count', 'Dashboard RSS Items', 'How many RSS items would you like to display on the dashboard ? ', 'text', '5', '5', '', 1, 1, '', 964),
('date_format', 'Date Format', 'How should dates be displayed across the website and control panel? Using the <a target="_blank" href="http://php.net/manual/en/function.date.php">date format</a> from PHP - OR - Using the format of <a target="_blank" href="http://php.net/manual/en/function.strftime.php">strings formatted as date</a> from PHP.', 'text', 'Y-m-d', '', '', 1, 1, '', 978),
('default_city', 'Default City', 'Default City used in adding new Client', 'text', 'Tudela', '', '', 1, 1, 'ImprovHealth', 0),
('default_province', 'Default Province', 'Default Province in adding new client', 'text', 'Misamis Occidental', '', '', 1, 1, 'ImprovHealth', 0),
('default_region', 'Default Region', 'Default Region in adding new client', 'text', 'Region 10', '', '', 1, 1, 'ImprovHealth', 0),
('default_theme', 'Default Theme', 'Select the theme you want users to see by default.', '', 'default', 'default', 'func:get_themes', 1, 0, '', 0),
('enable_comments', 'Enable Comments', 'Enable comments.', 'radio', '1', '0', '1=Enabled|0=Disabled', 0, 1, 'comments', 933),
('enable_profiles', 'Enable profiles', 'Allow users to add and edit profiles.', 'radio', '1', '', '1=Enabled|0=Disabled', 1, 1, 'users', 921),
('files_cache', 'Files Cache', 'When outputting an image via site.com/files what shall we set the cache expiration for?', 'select', '480', '480', '0=no-cache|1=1-minute|60=1-hour|180=3-hour|480=8-hour|1440=1-day|43200=30-days', 1, 1, 'files', 960),
('frontend_enabled', 'Site Status', 'Use this option to the user-facing part of the site on or off. Useful when you want to take the site down for maintenence', 'radio', '1', '1', '1=Open|0=Closed', 1, 1, '', 963),
('ga_email', 'Google Analytic E-mail', 'E-mail address used for Google Analytics, we need this to show the graph on the dashboard.', 'text', '', '', '', 0, 1, 'integration', 950),
('ga_password', 'Google Analytic Password', 'Google Analytics password. This is also needed this to show the graph on the dashboard.', 'password', '', '', '', 0, 1, 'integration', 949),
('ga_profile', 'Google Analytic Profile ID', 'Profile ID for this website in Google Analytics.', 'text', '', '', '', 0, 1, 'integration', 951),
('ga_tracking', 'Google Tracking Code', 'Enter your Google Analytic Tracking Code to activate Google Analytics view data capturing. E.g: UA-19483569-6', 'text', '', 'UA-19483569-6', '', 0, 1, 'integration', 952),
('improvhealth_code', 'ImprovHealth Code', 'This is the code/serial number for each BHS/RHU assigned. This is used for online communications between BHS and RHU.', 'text', 'IH000000', '', '', 1, 1, 'ImprovHealth', 0),
('mail_protocol', 'Mail Protocol', 'Select desired email protocol.', 'select', 'mail', 'mail', 'mail=Mail|sendmail=Sendmail|smtp=SMTP', 1, 1, 'email', 945),
('mail_sendmail_path', 'Sendmail Path', 'Path to server sendmail binary.', 'text', '', '', '', 0, 1, 'email', 937),
('mail_smtp_host', 'SMTP Host Name', 'The host name of your smtp server.', 'text', '', '', '', 0, 1, 'email', 941),
('mail_smtp_pass', 'SMTP Password', 'SMTP password.', 'password', '', '', '', 0, 1, 'email', 940),
('mail_smtp_port', 'SMTP Port', 'SMTP port number.', 'text', '', '', '', 0, 1, 'email', 939),
('mail_smtp_user', 'SMTP User Name', 'SMTP user name.', 'text', '', '', '', 0, 1, 'email', 938),
('meta_topic', 'Meta Topic', 'Two or three words describing this type of company/website.', 'text', 'Content Management', 'IMPROVHEALTH System Software, Touch Fo', '', 0, 1, '', 998),
('moderate_comments', 'Moderate Comments', 'Force comments to be approved before they appear on the site.', 'radio', '1', '0', '1=Enabled|0=Disabled', 0, 1, 'comments', 932),
('records_per_page', 'Records Per Page', 'How many records should we show per page in the admin section?', 'select', '25', '100', '10=10|25=25|50=50|100=100', 1, 1, '', 975),
('registered_email', 'User Registered Email', 'Send a notification email to the contact e-mail when someone registers.', 'radio', '1', '0', '1=Enabled|0=Disabled', 0, 1, 'users', 923),
('require_lastname', 'Require last names?', 'For some situations, a last name may not be required. Do you want to force users to enter one or not?', 'radio', '1', '', '1=Required|0=Optional', 1, 1, 'users', 924),
('rhu', 'RHU', 'Is Improvhealth installed in RHU?', 'radio', '0', '0', '1=Yes|0=No', 1, 1, 'ImprovHealth', 0),
('rss_feed_items', 'Feed item count', 'How many items should we show in RSS/blog feeds?', 'select', '25', '', '10=10|25=25|50=50|100=100', 1, 1, '', 970),
('server_email', 'Server E-mail', 'All e-mails to users will come from this e-mail address.', 'text', 'admin@localhost', '', '', 1, 1, 'email', 946),
('site_lang', 'Site Language', 'The native language of the website, used to choose templates of e-mail notifications, contact form, and other features that should not depend on the language of a user.', 'select', 'en', 'en', 'func:get_supported_lang', 1, 1, '', 997),
('site_name', 'Site Name', 'The name of the website for page titles and for use around the site.', 'text', 'Un-named Website', 'IMPROVHEALTH', '', 1, 1, '', 1000),
('site_public_lang', 'Public Languages', 'Which are the languages really supported and offered on the front-end of your website?', 'checkbox', 'en', 'en', 'func:get_supported_lang', 1, 1, '', 977),
('site_slogan', 'Site Slogan', 'The slogan of the website for page titles and for use around the site.', 'text', '', 'IMPROVHEALTH System Software, Touch Foundation, Inc.', '', 0, 1, '', 999),
('station', '', '', '', '', '', '', 0, 0, '', 0),
('station_address', 'Station Address', 'Complete Address of this ImprovHealth Installed', 'textarea', 'Centro Napo, Tudela, Misamis Occidental', '', '', 1, 1, 'ImprovHealth', 0),
('station_name', 'Station Name', 'Station Name/Barangay Name where RHU/BHS is located.', 'text', 'Tudela', '', '', 1, 1, 'ImprovHealth', 0),
('twitter_cache', 'Cache time', 'How many minutes should your Tweets be stored?', 'text', '300', '', '', 0, 1, 'twitter', 934),
('twitter_feed_count', 'Feed Count', 'How many tweets should be returned to the Twitter feed block?', 'text', '5', '', '', 0, 1, 'twitter', 935),
('twitter_username', 'Username', 'Twitter username.', 'text', '', '', '', 0, 1, 'twitter', 936),
('unavailable_message', 'Unavailable Message', 'When the site is turned off or there is a major problem, this message will show to users.', 'textarea', 'Sorry, this website is currently unavailable.', 'Sorry, IMPROVHEALTH is currently unavailable.', '', 0, 1, '', 962),
('version', 'Version', '', 'text', '1.0', '2.0.0', '', 0, 0, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `default_test`
--

CREATE TABLE IF NOT EXISTS `default_test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `category_id` int(11) NOT NULL,
  `attachment` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `intro` text COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `parsed` text COLLATE utf8_unicode_ci NOT NULL,
  `keywords` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `author_id` int(11) NOT NULL DEFAULT '0',
  `created_on` int(11) NOT NULL,
  `updated_on` int(11) NOT NULL DEFAULT '0',
  `comments_enabled` int(1) NOT NULL DEFAULT '1',
  `status` enum('draft','live') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `type` set('html','markdown','wysiwyg-advanced','wysiwyg-simple') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`),
  KEY `category_id - normal` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Blog posts.' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `default_test`
--


-- --------------------------------------------------------

--
-- Table structure for table `default_test_categories`
--

CREATE TABLE IF NOT EXISTS `default_test_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `title` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug - unique` (`slug`),
  UNIQUE KEY `title - unique` (`title`),
  KEY `slug - normal` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Blog Categories.' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `default_test_categories`
--


-- --------------------------------------------------------

--
-- Table structure for table `default_theme_options`
--

CREATE TABLE IF NOT EXISTS `default_theme_options` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `slug` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `type` set('text','textarea','password','select','select-multiple','radio','checkbox') COLLATE utf8_unicode_ci NOT NULL,
  `default` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `options` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_required` tinyint(1) NOT NULL,
  `theme` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Stores theme options.' AUTO_INCREMENT=8 ;

--
-- Dumping data for table `default_theme_options`
--

INSERT INTO `default_theme_options` (`id`, `slug`, `title`, `description`, `type`, `default`, `value`, `options`, `is_required`, `theme`) VALUES
(1, 'pyrocms_recent_comments', 'Recent Comments', 'Would you like to display recent comments on the dashboard?', 'radio', 'yes', 'yes', 'yes=Yes|no=No', 1, 'pyrocms'),
(2, 'pyrocms_news_feed', 'News Feed', 'Would you like to display the news feed on the dashboard?', 'radio', 'yes', 'yes', 'yes=Yes|no=No', 1, 'pyrocms'),
(3, 'pyrocms_quick_links', 'Quick Links', 'Would you like to display quick links on the dashboard?', 'radio', 'yes', 'yes', 'yes=Yes|no=No', 1, 'pyrocms'),
(4, 'pyrocms_analytics_graph', 'Analytics Graph', 'Would you like to display the graph on the dashboard?', 'radio', 'yes', 'yes', 'yes=Yes|no=No', 1, 'pyrocms'),
(5, 'show_breadcrumbs', 'Show Breadcrumbs', 'Would you like to display breadcrumbs?', 'radio', 'yes', 'yes', 'yes=Yes|no=No', 1, 'default'),
(6, 'layout', 'Layout', 'Which type of layout shall we use?', 'select', '2 column', '2 column', '2 column=Two Column|full-width=Full Width|full-width-home=Full Width Home Page', 1, 'default'),
(7, 'cufon_enabled', 'Use Cufon', 'Would you like to use Cufon for titles?', 'radio', 'yes', 'yes', 'yes=Yes|no=No', 1, 'default');

-- --------------------------------------------------------

--
-- Table structure for table `default_users`
--

CREATE TABLE IF NOT EXISTS `default_users` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `salt` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `group_id` int(11) DEFAULT NULL,
  `ip_address` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` int(1) DEFAULT NULL,
  `activation_code` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_on` int(11) NOT NULL,
  `last_login` int(11) NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `forgotten_password_code` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_code` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Registered User Information' AUTO_INCREMENT=13 ;

--
-- Dumping data for table `default_users`
--

INSERT INTO `default_users` (`id`, `email`, `password`, `salt`, `group_id`, `ip_address`, `active`, `activation_code`, `created_on`, `last_login`, `username`, `forgotten_password_code`, `remember_code`) VALUES
(1, 'ronaldrhey@gmail.com', '31c6b769c8a2d6d037d303c4ce9f7c15d5d3ab05', 'c0dd6', 1, '', 1, '', 1326243426, 1337389939, 'admin', NULL, NULL),
(2, 'msgeonzon@gmail.com', 'f8dcc1a9011b80a448d22e882c2f236d74c2e594', 'e64223', 3, '127.0.0.1', 1, NULL, 1326868200, 1329633799, 'reagan', NULL, NULL),
(3, 'chelsea@gmail.com', '9b6ba8e9da0b3482ee07a66d3bd788ba46744944', 'da2d7c', 3, '10.0.0.50', 1, NULL, 1329634011, 1329720044, 'chelsea', NULL, NULL),
(4, 'user@bhs.com', 'bbc2882846102c6ea285e8119401981932b756d0', 'e99937', 3, '127.0.0.1', 1, NULL, 1329714057, 1337405107, 'user', NULL, NULL),
(5, 'elenagilbert@gmail.com', '5b911485f0a6a8ad623dca48bb02366f41d42dc5', '4d02da', 4, '127.0.0.1', 1, NULL, 1332296368, 1332458281, 'dental', NULL, NULL),
(6, 'maricardulala@gmail.com', '3a4970d64eb701a943a01bbc152a2e3a53234b93', '096e32', 5, '127.0.0.1', 1, NULL, 1332296474, 1332458258, 'health', NULL, NULL),
(7, 'fervills@yahoo.com', '29e119ea772da3c8e7e04cb43a973fba924956b2', 'ce0a3f', 1, '127.0.0.1', 1, NULL, 1332419323, 1332419323, 'fervills', NULL, NULL),
(8, 'along@yahoo.com', '77f5bb5ce3e6953ee6c8b90987c1f061dedc9e10', '8961e1', 3, '127.0.0.1', 1, NULL, 1332419401, 1332419401, 'theway', NULL, NULL),
(9, 'alongg@yahoo.com', 'c9022b935ede5e3983a3195d457db9ad7f2a01b3', '13373a', 3, '127.0.0.1', 1, NULL, 1332419572, 1332419572, 'theway1', NULL, NULL),
(10, 'toheaven@yahoo.com', '7fe2d00bb7789367f1d135ac40069e3126fae167', '5aec34', 3, '127.0.0.1', 1, NULL, 1332419745, 1332419745, 'ervills', NULL, NULL),
(11, 'design@yahoo.com', '4797b244ec16105bf8206c3112ad75cb0a3f9308', '14b28b', 3, '127.0.0.1', 1, NULL, 1332419800, 1332419800, 'users', NULL, NULL),
(12, 'profile@yahoo.com', 'ee55d11d1e580d43ceac28c0707dfb6bb27cb95b', '53d126', 3, '127.0.0.1', 1, NULL, 1332419950, 1332419950, 'green', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `default_variables`
--

CREATE TABLE IF NOT EXISTS `default_variables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `default_variables`
--

INSERT INTO `default_variables` (`id`, `name`, `data`) VALUES
(1, 'x', '2');

-- --------------------------------------------------------

--
-- Table structure for table `default_widgets`
--

CREATE TABLE IF NOT EXISTS `default_widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `website` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `version` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `order` int(5) NOT NULL DEFAULT '0',
  `updated_on` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Dumping data for table `default_widgets`
--

INSERT INTO `default_widgets` (`id`, `slug`, `title`, `description`, `author`, `website`, `version`, `enabled`, `order`, `updated_on`) VALUES
(1, 'google_maps', 'a:5:{s:2:"en";s:11:"Google Maps";s:2:"el";s:19:"Χάρτης Google";s:2:"nl";s:11:"Google Maps";s:2:"br";s:11:"Google Maps";s:2:"ru";s:17:"Карты Google";}', 'a:5:{s:2:"en";s:32:"Display Google Maps on your site";s:2:"el";s:78:"Προβάλετε έναν Χάρτη Google στον ιστότοπό σας";s:2:"nl";s:27:"Toon Google Maps in uw site";s:2:"br";s:34:"Mostra mapas do Google no seu site";s:2:"ru";s:80:"Выводит карты Google на страницах вашего сайта";}', 'Gregory Athons', 'http://www.gregathons.com', '1.0', 1, 1, 1329637400),
(2, 'html', 's:4:"HTML";', 'a:5:{s:2:"en";s:28:"Create blocks of custom HTML";s:2:"el";s:80:"Δημιουργήστε περιοχές με δικό σας κώδικα HTML";s:2:"br";s:41:"Permite criar blocos de HTML customizados";s:2:"nl";s:30:"Maak blokken met maatwerk HTML";s:2:"ru";s:83:"Создание HTML-блоков с произвольным содержимым";}', 'Phil Sturgeon', 'http://philsturgeon.co.uk/', '1.0', 1, 2, 1329637400),
(3, 'login', 'a:5:{s:2:"en";s:5:"Login";s:2:"el";s:14:"Σύνδεση";s:2:"nl";s:5:"Login";s:2:"br";s:5:"Login";s:2:"ru";s:22:"Вход на сайт";}', 'a:5:{s:2:"en";s:36:"Display a simple login form anywhere";s:2:"el";s:96:"Προβάλετε μια απλή φόρμα σύνδεσης χρήστη οπουδήποτε";s:2:"br";s:69:"Permite colocar um formulário de login em qualquer lugar do seu site";s:2:"nl";s:32:"Toon overal een simpele loginbox";s:2:"ru";s:72:"Выводит простую форму для входа на сайт";}', 'Phil Sturgeon', 'http://philsturgeon.co.uk/', '1.0', 1, 3, 1329637400),
(4, 'navigation', 'a:5:{s:2:"en";s:10:"Navigation";s:2:"el";s:16:"Πλοήγηση";s:2:"nl";s:9:"Navigatie";s:2:"br";s:11:"Navegação";s:2:"ru";s:18:"Навигация";}', 'a:5:{s:2:"en";s:40:"Display a navigation group with a widget";s:2:"el";s:100:"Προβάλετε μια ομάδα στοιχείων πλοήγησης στον ιστότοπο";s:2:"nl";s:38:"Toon een navigatiegroep met een widget";s:2:"br";s:62:"Exibe um grupo de links de navegação como widget em seu site";s:2:"ru";s:88:"Отображает навигационную группу внутри виджета";}', 'Phil Sturgeon', 'http://philsturgeon.co.uk/', '1.0', 1, 4, 1329637400),
(5, 'rss_feed', 'a:5:{s:2:"en";s:8:"RSS Feed";s:2:"el";s:24:"Τροφοδοσία RSS";s:2:"nl";s:8:"RSS Feed";s:2:"br";s:8:"Feed RSS";s:2:"ru";s:31:"Лента новостей RSS";}', 'a:5:{s:2:"en";s:41:"Display parsed RSS feeds on your websites";s:2:"el";s:82:"Προβάλετε τα περιεχόμενα μιας τροφοδοσίας RSS";s:2:"nl";s:28:"Toon RSS feeds op uw website";s:2:"br";s:48:"Interpreta e exibe qualquer feed RSS no seu site";s:2:"ru";s:94:"Выводит обработанную ленту новостей на вашем сайте";}', 'Phil Sturgeon', 'http://philsturgeon.co.uk/', '1.2', 1, 5, 1329637400),
(6, 'social_bookmark', 'a:5:{s:2:"en";s:15:"Social Bookmark";s:2:"el";s:35:"Κοινωνική δικτύωση";s:2:"nl";s:19:"Sociale Bladwijzers";s:2:"br";s:15:"Social Bookmark";s:2:"ru";s:37:"Социальные закладки";}', 'a:5:{s:2:"en";s:47:"Configurable social bookmark links from AddThis";s:2:"el";s:111:"Παραμετροποιήσιμα στοιχεία κοινωνικής δικτυώσης από το AddThis";s:2:"nl";s:43:"Voeg sociale bladwijzers toe vanuit AddThis";s:2:"br";s:87:"Adiciona links de redes sociais usando o AddThis, podendo fazer algumas configurações";s:2:"ru";s:90:"Конфигурируемые социальные закладки с сайта AddThis";}', 'Phil Sturgeon', 'http://philsturgeon.co.uk/', '1.0', 1, 6, 1329637400),
(7, 'twitter_feed', 'a:5:{s:2:"en";s:12:"Twitter Feed";s:2:"el";s:14:"Ροή Twitter";s:2:"nl";s:11:"Twitterfeed";s:2:"br";s:15:"Feed do Twitter";s:2:"ru";s:21:"Лента Twitter''а";}', 'a:5:{s:2:"en";s:37:"Display Twitter feeds on your website";s:2:"el";s:69:"Προβολή των τελευταίων tweets από το Twitter";s:2:"nl";s:31:"Toon Twitterfeeds op uw website";s:2:"br";s:64:"Mostra os últimos tweets de um usuário do Twitter no seu site.";s:2:"ru";s:98:"Выводит ленту новостей Twitter на страницах вашего сайта";}', 'Phil Sturgeon', 'http://philsturgeon.co.uk/', '1.2', 1, 7, 1329637400),
(8, 'archive', 'a:3:{s:2:"en";s:7:"Archive";s:2:"br";s:15:"Arquivo do Blog";s:2:"ru";s:10:"Архив";}', 'a:3:{s:2:"en";s:64:"Display a list of old months with links to posts in those months";s:2:"br";s:95:"Mostra uma lista navegação cronológica contendo o índice dos artigos publicados mensalmente";s:2:"ru";s:114:"Выводит список по месяцам со ссылками на записи в этих месяцах";}', 'Phil Sturgeon', 'http://philsturgeon.co.uk/', '1.0', 1, 8, 1329637400),
(9, 'blog_categories', 'a:3:{s:2:"en";s:15:"Blog Categories";s:2:"br";s:18:"Categorias do Blog";s:2:"ru";s:29:"Категории Блога";}', 'a:3:{s:2:"en";s:30:"Show a list of blog categories";s:2:"br";s:57:"Mostra uma lista de navegação com as categorias do Blog";s:2:"ru";s:57:"Выводит список категорий блога";}', 'Stephen Cozart', 'http://github.com/clip/', '1.0', 1, 9, 1329637400),
(10, 'latest_posts', 'a:3:{s:2:"en";s:12:"Latest posts";s:2:"br";s:24:"Artigos recentes do Blog";s:2:"ru";s:31:"Последние записи";}', 'a:3:{s:2:"en";s:39:"Display latest blog posts with a widget";s:2:"br";s:81:"Mostra uma lista de navegação para abrir os últimos artigos publicados no Blog";s:2:"ru";s:100:"Выводит список последних записей блога внутри виджета";}', 'Erik Berman', 'http://www.nukleo.fr', '1.0', 1, 10, 1329637400);

-- --------------------------------------------------------

--
-- Table structure for table `default_widget_areas`
--

CREATE TABLE IF NOT EXISTS `default_widget_areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_slug` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `default_widget_areas`
--

INSERT INTO `default_widget_areas` (`id`, `slug`, `title`) VALUES
(1, 'sidebar', 'Sidebar');

-- --------------------------------------------------------

--
-- Table structure for table `default_widget_instances`
--

CREATE TABLE IF NOT EXISTS `default_widget_instances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `widget_id` int(11) DEFAULT NULL,
  `widget_area_id` int(11) DEFAULT NULL,
  `options` text COLLATE utf8_unicode_ci NOT NULL,
  `order` int(10) NOT NULL DEFAULT '0',
  `created_on` int(11) NOT NULL DEFAULT '0',
  `updated_on` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `default_widget_instances`
--


--
-- Constraints for dumped tables
--

--
-- Constraints for table `default_children_under_one`
--
ALTER TABLE `default_children_under_one`
  ADD CONSTRAINT `default_children_under_one_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `default_clients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `default_family_planning`
--
ALTER TABLE `default_family_planning`
  ADD CONSTRAINT `default_family_planning_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `default_clients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `default_family_planning_visits`
--
ALTER TABLE `default_family_planning_visits`
  ADD CONSTRAINT `default_family_planning_visits_ibfk_1` FOREIGN KEY (`fp_id`) REFERENCES `default_family_planning` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `default_postpartum`
--
ALTER TABLE `default_postpartum`
  ADD CONSTRAINT `default_postpartum_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `default_clients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `default_prenatals`
--
ALTER TABLE `default_prenatals`
  ADD CONSTRAINT `default_prenatals_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `default_clients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `default_sc`
--
ALTER TABLE `default_sc`
  ADD CONSTRAINT `default_sc_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `default_clients` (`id`) ON DELETE CASCADE;
