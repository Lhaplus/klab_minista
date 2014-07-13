SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+09:00";
USE minista;
CREATE TABLE IF NOT EXISTS `ng_word` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ng_word` varchar(256) NOT NULL,
  `timestamp_int` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=mroonga DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
