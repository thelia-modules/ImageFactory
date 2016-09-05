ALTER TABLE `image_factory` ADD  `allow_zoom` TINYINT(1) NOT NULL DEFAULT '0' AFTER `persist`;
ALTER TABLE `image_factory` ADD  `priority` INTEGER DEFAULT '0' AFTER `code`;