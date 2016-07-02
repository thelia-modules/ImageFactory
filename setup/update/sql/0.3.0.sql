ALTER TABLE `image_factory` ADD `resampling_filter` VARCHAR(55) NOT NULL DEFAULT 'undefined' AFTER `rotation`;
ALTER TABLE `image_factory` ADD  `disable_i18n_processing` TINYINT(1) NOT NULL DEFAULT '0' AFTER `image_not_found_destination_file_name`;
ALTER TABLE `image_factory` ADD  `just_symlink` TINYINT(1) NOT NULL DEFAULT '0' AFTER `destination`;