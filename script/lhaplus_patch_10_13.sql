USE minista;
CREATE TABLE IF NOT EXISTS `view_ranking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL,
  `point` int(11) NOT NULL,
  `term` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `term_date` (`term`,`date`)
) ENGINE=mroonga DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `vote_ranking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL,
  `point` int(11) NOT NULL,
  `term` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `term_date` (`term`,`date`)
) ENGINE=mroonga DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `favorite_ranking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL,
  `point` int(11) NOT NULL,
  `term` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `term_date` (`term`,`date`)
) ENGINE=mroonga DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

INSERT INTO `category` (`id`, `name`, `regist_count`) VALUES
(1, 'Life', 0),
(2, 'Business', 0),
(3, 'Girls', 0),
(4, 'Teen', 0),
(5, 'Entertainment', 0);
