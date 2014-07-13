USE minista;
ALTER TABLE `categorizing` ADD INDEX `article_id` (`article_id`);
ALTER TABLE `tagging` ADD INDEX `article_id` (`article_id`);
