# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.37)
# Database: tone_analyzer
# Generation Time: 2019-11-12 03:35:45 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table twitter_hashtag_trending
# ------------------------------------------------------------

DROP TABLE IF EXISTS `twitter_hashtag_trending`;

CREATE TABLE `twitter_hashtag_trending` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` text,
  `url` text,
  `query` text,
  `promoted_content` int(11) DEFAULT NULL,
  `tweet_volume` int(11) DEFAULT NULL,
  `src_id` text,
  `update_date` datetime DEFAULT NULL,
  `trend_id` bigint(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table twitter_keys
# ------------------------------------------------------------

DROP TABLE IF EXISTS `twitter_keys`;

CREATE TABLE `twitter_keys` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `token` text,
  `token_secret` text,
  `consumer_key` text,
  `consumer_secret` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table twitter_sentences
# ------------------------------------------------------------

DROP TABLE IF EXISTS `twitter_sentences`;

CREATE TABLE `twitter_sentences` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `text_str` longtext,
  `update_date` datetime DEFAULT NULL,
  `tid` bigint(20) DEFAULT NULL,
  `src` text,
  `type` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table twitter_sentences_scores
# ------------------------------------------------------------

DROP TABLE IF EXISTS `twitter_sentences_scores`;

CREATE TABLE `twitter_sentences_scores` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `text_str` longtext,
  `score` decimal(10,6) DEFAULT NULL,
  `name` text,
  `update_date` datetime DEFAULT NULL,
  `tid` bigint(11) DEFAULT NULL,
  `src` text,
  `type` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table twitter_tone
# ------------------------------------------------------------

DROP TABLE IF EXISTS `twitter_tone`;

CREATE TABLE `twitter_tone` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_str` bigint(11) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `text_str` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `src` text,
  `type` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table twitter_tone_scores
# ------------------------------------------------------------

DROP TABLE IF EXISTS `twitter_tone_scores`;

CREATE TABLE `twitter_tone_scores` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `score` decimal(10,6) DEFAULT NULL,
  `name` text,
  `update_date` datetime DEFAULT NULL,
  `src` text,
  `type` text,
  `src_id` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table update_date_time
# ------------------------------------------------------------

DROP TABLE IF EXISTS `update_date_time`;

CREATE TABLE `update_date_time` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `update_date` datetime DEFAULT NULL,
  `source` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table watson_keys
# ------------------------------------------------------------

DROP TABLE IF EXISTS `watson_keys`;

CREATE TABLE `watson_keys` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `api_key` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
