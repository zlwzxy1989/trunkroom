-- phpMyAdmin SQL Dump
-- version 4.0.10.12
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2016-07-15 00:15:27
-- 服务器版本: 5.1.73
-- PHP 版本: 5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `homework`
--

-- --------------------------------------------------------

--
-- 表的结构 `shop`
--

CREATE TABLE IF NOT EXISTS `shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '店舗名',
  `location` varchar(150) NOT NULL DEFAULT '' COMMENT '住所(アクセス含む)',
  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '電話番号',
  `shop_comment` text NOT NULL COMMENT '店舗説明文',
  `money_comment` text NOT NULL COMMENT '料金表の下の説明文',
  `img` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `url` (`url`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='店舗テーブル' AUTO_INCREMENT=61 ;

-- --------------------------------------------------------

--
-- 表的结构 `site`
--

CREATE TABLE IF NOT EXISTS `site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='会社テーブル' AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `trunk_room`
--

CREATE TABLE IF NOT EXISTS `trunk_room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL DEFAULT '0',
  `shop_id` int(11) NOT NULL DEFAULT '0',
  `room_type` varchar(20) NOT NULL DEFAULT '',
  `space` decimal(4,2) NOT NULL DEFAULT '0.00' COMMENT '広さ(約畳）',
  `volume` varchar(30) NOT NULL DEFAULT '' COMMENT '広さ(幅ｘ奥行ｘ高さ)',
  `price` int(11) NOT NULL DEFAULT '0' COMMENT '月額料金',
  `campaign_price` int(11) NOT NULL DEFAULT '0' COMMENT 'キャンペーン料金',
  `status` varchar(20) NOT NULL DEFAULT '' COMMENT '空き状況',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `shop_id` (`shop_id`,`room_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='トランクルームテーブル' AUTO_INCREMENT=930 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
