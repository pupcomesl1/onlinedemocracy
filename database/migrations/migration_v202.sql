USE onlinedemocracy;
ALTER TABLE `users` 
ADD COLUMN `languageCode` VARCHAR(2) NOT NULL DEFAULT 'en' COMMENT '' AFTER `roleId`;