# 0.4.3

- #25 Fix setup procedure

# 0.4.2

- #24 Fix bug, when using the prefix and suffix

# 0.4.1

- #22 Fix bug, when a image path contains a special character

# 0.4.0

- #21 Update command `image-factory:generate-destination`, add simple syntax to target all factories
- #20 Add option "priority"
- #19 Add argument out on the smarty method

# 0.3.1

- #15 Fix possible bug when the source directory does not exist

# 0.3.0

- #14 Fix factory resolver by url, it's possible to have a same destination for the factories who use a prefix or suffix
- #14 Add option `just_symlink`
- #14 Add option `disable_i18n_processing` on the smarty plugin `image_factory`
- #14 Add support for the product sale element on the smarty plugin `image_factory`
- #14 Add new command `image-factory:reload-factory`
- #14 Add option `resampling_filter`
- #14 Add new command `image-factory:generate-destination`

# 0.2.5

- #11 #12 Fix i18n processing for the smarty plugin
- #10 Add possibility to directly set a path into the method `getUrl` on the `FactoryHandler`. Thanks @Alban-io !

# 0.2.4

- #9 Fix setup directory path. Thanks @Alban-io !

# 0.2.2, 0.2.3

- #4, #5 Fix method `ImageFactory\Handler\FactoryHandler::getPathByClassName`, wrong path was returned. Thanks @Yochima !

# 0.2.1

- #3 Fix empty file_name behavior in {image_factory}

# 0.2.0

- Adds the configuration of the background color
- Adds the configuration of the background opacity
- Adds the configuration of the image not found path
- Adds the configuration of the image not found destination file name