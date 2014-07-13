USE minista;
ALTER TABLE `article_folder` ADD COLUMN `delete_time` int(11) DEFAULT NULL;
ALTER TABLE `article_folder` ADD COLUMN `delete_operator_id` int(11) DEFAULT NULL;
ALTER TABLE `my_article` ADD COLUMN `delete_time` int(11) DEFAULT NULL;
ALTER TABLE `my_article` ADD COLUMN `delete_operator_id` int(11) DEFAULT NULL;
ALTER TABLE `my_item` ADD COLUMN `delete_time` int(11) DEFAULT NULL;
ALTER TABLE `my_item` ADD COLUMN `delete_operator_id` int(11) DEFAULT NULL;
