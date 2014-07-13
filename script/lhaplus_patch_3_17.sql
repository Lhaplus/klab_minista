ALTER TABLE `article` ADD COLUMN `delete_time` int(11) DEFAULT NULL;
ALTER TABLE `article` ADD COLUMN `delete_operator_id` int(11) DEFAULT NULL;
ALTER TABLE `item` ADD COLUMN `delete_time` int(11) DEFAULT NULL;
ALTER TABLE `item` ADD COLUMN `delete_operator_id` int(11) DEFAULT NULL;
ALTER TABLE `tag` ADD KEY `name_key` (`name`);

CREATE TABLE IF NOT EXISTS `search_keyword_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `keyword` varchar(256) NOT NULL,
  `timestamp_int` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  FULLTEXT KEY `keyword` (`keyword`) COMMENT 'parser "TokenMecab"',
  KEY `timestamp_int` (`timestamp_int`)
) ENGINE=mroonga DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
