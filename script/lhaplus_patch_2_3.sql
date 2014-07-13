DROP INDEX `term_date` ON `view_ranking`;
DROP INDEX `term_date` ON `favorite_ranking`;
ALTER TABLE `view_ranking` ADD KEY `category_id_term_timestamp_int` (`category_id`,`term`,`timestamp_int`);
ALTER TABLE `favorite_ranking` ADD KEY `category_id_term_timestamp_int` (`category_id`,`term`,`timestamp_int`);