USE minista;
ALTER TABLE `item` ADD COLUMN `explanation` VARCHAR(256) NOT NULL;
ALTER TABLE `item` MODIFY `explanation` VARCHAR(256) NOT NULL AFTER `title`;
ALTER TABLE `my_item` ADD COLUMN `explanation` VARCHAR(256) NOT NULL;
ALTER TABLE `my_item` MODIFY `explanation` VARCHAR(256) NOT NULL AFTER `title`;
