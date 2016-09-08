-- このファイルを使ってデータベースの構造と商品データをインポートできます。

-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-9-7 23:29
-- サーバのバージョン： 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hamazon`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `id` int(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `kana` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `price` int(20) NOT NULL,
  `category` varchar(30) NOT NULL,
  `date` date NOT NULL,
  `details` text NOT NULL,
  `stock` int(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `items`
--

INSERT INTO `items` (`id`, `name`, `kana`, `deleted`, `price`, `category`, `date`, `details`, `stock`) VALUES
(1, '東京電機大学千住キャンパス1号館', 'とうきょうでんきだいがくせんじゅきゃんぱす１ごうかん', 0, 500000000, '1', '2015-06-25', '最先端設備を備え、最新技術を駆使して省CO2エコキャンパスを実現するとともに、免震、制震、非常用設備など防災機能を充実させたキャンパスです。キャンパス内の図書館やカフェ、3つのプラザなど地域の人々にも開放され、人と緑にあふれた優しいまちづくりにも積極的に参加していきます。勉強の合間の気分転換にピッタリなルーフガーデン、そしてカフェラウンジ、食堂は、どの建物からでも行きやすい場所に配置。学生の過ごしやすさを第一に考えたキャンパスです。（製造者より）', 1),
(2, '東京電機大学千住キャンパス2号館', 'とうきょうでんきだいがくせんじゅきゃんぱす2ごうかん', 0, 400000000, '1', '2016-03-28', '2号館です。北千住動物公園が備わっています。', 10),
(3, '東京電機大学千住キャンパス3号館', 'とうきょうでんきだいがくせんじゅきゃんぱす3ごうかん', 0, 400000000, '1', '2016-03-29', '3号館です。名物ストリームスポーツ「席確保」のホームスタジアムです。', 20);

-- --------------------------------------------------------

--
-- テーブルの構造 `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int(6) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `total_price` int(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `order_details`
--

CREATE TABLE IF NOT EXISTS `order_details` (
  `detail_id` int(10) NOT NULL,
  `order_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `priced` int(10) NOT NULL,
  `quantity` int(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `reviews`
--

CREATE TABLE IF NOT EXISTS `reviews` (
  `review_id` int(10) NOT NULL,
  `item_id` int(4) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `rate` int(1) NOT NULL,
  `title` varchar(20) NOT NULL DEFAULT 'タイトルなし',
  `content` text NOT NULL,
  `likes` int(4) NOT NULL DEFAULT '0',
  `dislikes` int(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

--
-- テーブルの構造 `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `last_login` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`detail_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
