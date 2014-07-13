USE minista;
ALTER TABLE `view_ranking` CHANGE COLUMN `date` `timestamp_int` int(11) NOT NULL;
ALTER TABLE `vote_ranking` CHANGE COLUMN `date` `timestamp_int` int(11) NOT NULL;
ALTER TABLE `favorite_ranking` CHANGE COLUMN `date` `timestamp_int` int(11) NOT NULL;
