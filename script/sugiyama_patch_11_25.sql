USE minista;
ALTER TABLE `article` ADD COLUMN `image_author` varchar(256);
ALTER TABLE `my_article` ADD COLUMN `image_author` varchar(256);
ALTER TABLE `article` ADD COLUMN `image_title` varchar(512);
ALTER TABLE `my_article` ADD COLUMN `image_title` varchar(512);
