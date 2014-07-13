ALTER TABLE `view_history` ADD COLUMN `category_id` int(11) NOT NULL;
ALTER TABLE `view_history` MODIFY `category_id` int(11) NOT NULL AFTER `article_id`;
ALTER TABLE `favorite_history` ADD COLUMN `category_id` int(11) NOT NULL;
ALTER TABLE `favorite_history` MODIFY `category_id` int(11) NOT NULL AFTER `article_id`;
ALTER TABLE `view_ranking` ADD COLUMN `category_id` int(11) NOT NULL;
ALTER TABLE `view_ranking` MODIFY `category_id` int(11) NOT NULL AFTER `article_id`;
ALTER TABLE `favorite_ranking` ADD COLUMN `category_id` int(11) NOT NULL;
ALTER TABLE `favorite_ranking` MODIFY `category_id` int(11) NOT NULL AFTER `article_id`;