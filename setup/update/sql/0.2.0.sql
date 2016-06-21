ALTER TABLE `image_factory` ADD `background_color` CHAR(6) NOT NULL DEFAULT 'FFFFFF' AFTER `quality` ;
ALTER TABLE `image_factory` ADD `background_opacity` TINYINT NOT NULL DEFAULT '100' AFTER `background_color` ;
ALTER TABLE `image_factory` ADD `image_not_found_source` VARCHAR(255) NOT NULL AFTER `imagine_library_code`;
ALTER TABLE `image_factory` ADD `image_not_found_destination_file_name` VARCHAR(255) NOT NULL AFTER `image_not_found_source`;