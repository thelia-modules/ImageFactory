
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- image_factory
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `image_factory`;

CREATE TABLE `image_factory`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(255) NOT NULL,
    `sources` TEXT NOT NULL,
    `destination` VARCHAR(255),
    `width` INTEGER NOT NULL,
    `height` INTEGER NOT NULL,
    `quality` TINYINT DEFAULT 75 NOT NULL,
    `resize_mode` VARCHAR(55) DEFAULT 'exact_ratio_with_borders',
    `rotation` TINYINT DEFAULT 0,
    `prefix` VARCHAR(55),
    `suffix` VARCHAR(55),
    `layers` TEXT,
    `effects` TEXT,
    `pixel_ratios` TEXT,
    `interlace` VARCHAR(55) DEFAULT 'none',
    `persist` TINYINT(1) DEFAULT 1,
    `imagine_library_code` VARCHAR(255) DEFAULT 'gd',
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `image_factory_U_1` (`code`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- image_factory_i18n
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `image_factory_i18n`;

CREATE TABLE `image_factory_i18n`
(
    `id` INTEGER NOT NULL,
    `locale` VARCHAR(5) DEFAULT 'en_US' NOT NULL,
    `title` VARCHAR(255),
    `description` LONGTEXT,
    PRIMARY KEY (`id`,`locale`),
    CONSTRAINT `image_factory_i18n_FK_1`
        FOREIGN KEY (`id`)
        REFERENCES `image_factory` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
