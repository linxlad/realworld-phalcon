CREATE TABLE
IF NOT EXISTS `user` (
	`id` BIGINT (20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`username` VARCHAR (64) COLLATE utf8_bin DEFAULT NULL,
	`email` VARCHAR (48) COLLATE utf8_bin NOT NULL,
	`password` VARCHAR (128) COLLATE utf8_bin NOT NULL,
	`bio` text COLLATE utf8_bin DEFAULT NULL,
	`image` text COLLATE utf8_bin DEFAULT NULL,
	`token` text COLLATE utf8_bin DEFAULT NULL,
	`token_expires` datetime DEFAULT NULL,
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `username` (`username`),
	KEY `email` (`email`)
) ENGINE = INNODB DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

ALTER TABLE `user` ADD UNIQUE `unique_uername_email` (`username`, `email`);

CREATE TRIGGER `user_created` BEFORE INSERT ON `user` FOR EACH ROW
SET NEW.created = IFNULL(NEW.created, NOW()),
 NEW.created = NOW();

CREATE TRIGGER `user_modified` BEFORE UPDATE ON `user` FOR EACH ROW
SET NEW.modified = IFNULL(NEW.modified, NOW()),
 NEW.modified = NOW();

CREATE TABLE
IF NOT EXISTS `articles` (
	`id` BIGINT (20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`user_id` BIGINT (20) UNSIGNED NOT NULL,
	`slug` VARCHAR (255) COLLATE utf8_bin UNIQUE NOT NULL,
	`title` VARCHAR (255) COLLATE utf8_bin NOT NULL,
	`description` VARCHAR (255) COLLATE utf8_bin NOT NULL,
	`body` text COLLATE utf8_bin NOT NULL,
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,
	PRIMARY KEY (`id`),
	CONSTRAINT `articles_ibfk_1` FOREIGN KEY fk_user_id (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE = INNODB DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

CREATE TRIGGER `articles_created` BEFORE INSERT ON `articles` FOR EACH ROW
SET NEW.created = IFNULL(NEW.created, NOW()),
 NEW.created = NOW();

CREATE TRIGGER `articles_modified` BEFORE UPDATE ON `articles` FOR EACH ROW
SET NEW.modified = IFNULL(NEW.modified, NOW()),
 NEW.modified = NOW();

CREATE TABLE
IF NOT EXISTS `comments` (
	`id` BIGINT (20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`user_id` BIGINT (20) UNSIGNED NOT NULL,
	`article_id` BIGINT (20) UNSIGNED NOT NULL,
	`body` text COLLATE utf8_bin NOT NULL,
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,
	PRIMARY KEY (`id`),
	CONSTRAINT `comments_ibfk_1` FOREIGN KEY fk_user_id (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
	CONSTRAINT `comments_ibfk_2` FOREIGN KEY fk_article_id (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE
) ENGINE = INNODB DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

CREATE TRIGGER `comments_created` BEFORE INSERT ON `comments` FOR EACH ROW
SET NEW.created = IFNULL(NEW.created, NOW()),
 NEW.created = NOW();

CREATE TRIGGER `comments_modified` BEFORE UPDATE ON `comments` FOR EACH ROW
SET NEW.modified = IFNULL(NEW.modified, NOW()),
 NEW.modified = NOW();

CREATE TABLE
IF NOT EXISTS `tags` (
	`id` BIGINT (20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR (255) COLLATE utf8_bin UNIQUE NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE = INNODB DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

CREATE TABLE
IF NOT EXISTS `article_tag` (
	`article_id` BIGINT (20) UNSIGNED NOT NULL,
	`tag_id` BIGINT (20) UNSIGNED NOT NULL,
	PRIMARY KEY (`article_id`, `tag_id`),
	CONSTRAINT `article_tag_ibfk_1` FOREIGN KEY fk_article_id (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
	CONSTRAINT `article_tag_ibfk_2` FOREIGN KEY fk_tag_id (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE = INNODB DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

CREATE TABLE
IF NOT EXISTS `favorites` (
	`user_id` BIGINT (20) UNSIGNED NOT NULL,
	`article_id` BIGINT (20) UNSIGNED NOT NULL,
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,
	PRIMARY KEY (`user_id`, `article_id`),
	CONSTRAINT `favorites_ibfk_1` FOREIGN KEY fk_user_id (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
	CONSTRAINT `favorites_ibfk_2` FOREIGN KEY fk_article_id (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE
) ENGINE = INNODB DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

CREATE TRIGGER `favorites_created` BEFORE INSERT ON `favorites` FOR EACH ROW
SET NEW.created = IFNULL(NEW.created, NOW()),
 NEW.created = NOW();

CREATE TRIGGER `favorites_modified` BEFORE UPDATE ON `favorites` FOR EACH ROW
SET NEW.modified = IFNULL(NEW.modified, NOW()),
 NEW.modified = NOW();

CREATE TABLE
IF NOT EXISTS `follows` (
	`follower_id` BIGINT (20) UNSIGNED NOT NULL,
	`followed_id` BIGINT (20) UNSIGNED NOT NULL,
	`created` datetime DEFAULT NULL,
	`modified` datetime DEFAULT NULL,
	PRIMARY KEY (
		`follower_id`,
		`followed_id`
	),
	CONSTRAINT `follows_ibfk_1` FOREIGN KEY fk_follower_id (`follower_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
	CONSTRAINT `follows_ibfk_2` FOREIGN KEY fk_followed_id (`followed_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE = INNODB DEFAULT CHARSET = utf8 COLLATE = utf8_bin;

CREATE TRIGGER `follows_created` BEFORE INSERT ON `follows` FOR EACH ROW
SET NEW.created = IFNULL(NEW.created, NOW()),
 NEW.created = NOW();

CREATE TRIGGER `follows_modified` BEFORE UPDATE ON `follows` FOR EACH ROW
SET NEW.modified = IFNULL(NEW.modified, NOW()),
 NEW.modified = NOW();