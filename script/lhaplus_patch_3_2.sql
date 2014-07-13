CREATE TABLE IF NOT EXISTS `guest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(256) NOT NULL,
  `user_agent` varchar(256) NOT NULL,
  `first_article_id` int(11) NOT NULL,
  `timestamp_int` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ip_address` (`ip_address`)
) ENGINE=mroonga DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `guest_view_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guest_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `timestamp_int` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `guest_id` (`guest_id`),
  KEY `timestamp_int` (`timestamp_int`),
  KEY `article_id_timestamp_int` (`article_id`,`timestamp_int`)
) ENGINE=mroonga DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

ALTER TABLE `view_history` ADD KEY `article_id_timestamp_int` (`article_id`,`timestamp_int`);
ALTER TABLE `vote_history` ADD KEY `article_id_timestamp_int` (`article_id`,`timestamp_int`);
ALTER TABLE `favorite_history` ADD KEY `article_id_timestamp_int` (`article_id`,`timestamp_int`);