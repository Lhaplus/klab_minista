ALTER TABLE `category` ADD COLUMN `css_class` VARCHAR(256) NOT NULL;
INSERT INTO `category` (`name`, `j_name`, `regist_count`, `css_class`) VALUES
('Life', 'ライフ', 0, 'l'),
('Business', 'ビジネス', 0, 'b'),
('Girls', 'ガール', 0, 'g'),
('Teen', 'ティーン', 0, 't'),
('Entertainment', 'エンタメ', 0, 'e');
UPDATE `category` SET `j_name`='ライフ' WHERE `name`='Life';
UPDATE `category` SET `j_name`='ビジネス' WHERE `name`='Business';
UPDATE `category` SET `j_name`='ガール' WHERE `name`='Girls';
UPDATE `category` SET `j_name`='ティーン' WHERE `name`='Teen';
UPDATE `category` SET `j_name`='エンタメ' WHERE `name`='Entertainment';
UPDATE `category` SET `css_class`='l' WHERE `name`='Life';
UPDATE `category` SET `css_class`='b' WHERE `name`='Business';
UPDATE `category` SET `css_class`='g' WHERE `name`='Girls';
UPDATE `category` SET `css_class`='t' WHERE `name`='Teen';
UPDATE `category` SET `css_class`='e' WHERE `name`='Entertainment';