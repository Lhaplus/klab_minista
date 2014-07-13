USE minista;
ALTER TABLE `user` ADD COLUMN `folder_max_count` int default 5;
ALTER TABLE `article_folder` ADD COLUMN `article_max_count` int default 100;
