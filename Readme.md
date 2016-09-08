# Image Factory

This module provides an alternative to managing images in Thelia.

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is ImageFactory.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/image-factory-module:~0.4.0
```

## Usage

With Smarty
```smarty
{* With product id *}
<ul>
  {image_factory attr=['class'=> 'example-1'] code='test' view="product" view_id="325" inner="<li>?</li>" limit=10}
</ul>

{* With product sale element id *}
<ul>
  {image_factory attr=['class'=> 'example-1'] code='test' view="product_sale_element" view_id="21" inner="<li>?</li>" limit=10}
</ul>

{* With image id *}
<ul>
  {image_factory attr=['class'=> 'example-2'] code='test' view="product" image_id="10,11,12,13,14" inner="<li>?</li>"}
</ul>

{* With image file name *}
<ul>
  {image_factory attr=['class'=> 'example-3'] code='test' file_name="sample-image-394.png,sample-image-396.png" inner="<li>?</li>"}
</ul>

{* With force image not found *}
<ul>
  {image_factory force_not_found=true code='test' file_name="an-image-which-does-not-exist"}
</ul>


{* With an variable *}

{image_factory out="images" force_not_found=true code='test' file_name="an-image-which-does-not-exist"}

{$images|var_dump} {* $images is an collection of type ImageFactory\Entity\FactoryEntityCollection *}
{$images[0]|var_dump} {* $images[0] is an object of type ImageFactory\Entity\FactoryEntity *}

{* It's possibale to iterate on the collection *}

{foreach from=$images key=k item=image}
 {$image|var_dump}
{/foreach}

{* The object FactoryEntity implements the method __toString. This method returns the html code. *}

{foreach from=$images key=k item=image}
 {$image} {* Display the html code *}
{/foreach}
```

With PHP
```php
    /** @var \ImageFactory\Handler\FactoryHandler $factoryHandler */
    $factoryHandler = $this->getContainer()->get('image_factory.factory_handler');

    $image = ProductImageQuery::create()->findOne();
    $factoryCode = 'test';

    $url = $factoryHandler->getUrl($factoryCode, null, 'path/your/image');
    $url = $factoryHandler->getUrl($factoryCode, $image);

    $uri = $factoryHandler->getUri($factoryCode, null, 'path/your/image');
    $uri = $factoryHandler->getUri($factoryCode, $image);
```

## Commands

#### The command image-factory:generate-destination

For generate all images of a specific factory

```shell
    php Thelia image-factory:generate-destination product-high,product-medium,product-small
```

Or for all factories

```shell
    php Thelia image-factory:generate-destination "*"
```

With this command, the images already present on the destination paths will not be regenerated.
It's possible to force the generation process by adding the option `--force`.

```shell
    php Thelia image-factory:generate-destination product-high,product-medium,product-small --force
```

#### The command image-factory:reload-factory

For reload all factories in the cache

```shell
    php Thelia image-factory:reload-factory
```

#### Configure your factories on your database (table image_factory)
        
| Column        | Description           | Type          |
|:-------------:|:-------------:|:-----:|
| code      | The code of the factory | Text (Example : "product-medium") |
| priority      | Loading priority      |   Integer (Example : 4, Default : 0) |
| sources | A list of sources path    |    Text (Example : "  my/path/1  \|  my/path/2  ") |
| destination | A destination path    |    Text (Example : "images/product/medium") |
| just_symlink | For ignore the processing and create a symlink to the source file.    |    Boolean (Default : 0) |
| width | The width of the destination image    |    Integer (Example : 400) |
| height | The height of the destination image    |    Integer (Example : 400) |
| quality | The quality of the destination image. From 1 to 100    |    Integer (Example : 90) |
| background_color | The color applied to empty image parts during processing. Use rgb or rrggbb color format    |    Integer (Example : "000000", Default : "FFFFFF") |
| background_opacity | The opacity applied to the background color. From 0 to 100   |    Integer (Example : 90, Default: 100) |
| resize_mode | If 'exact_ratio_with_crop', the image will have the exact specified width and height, and will be cropped if required. If the source image is smaller than the required width and/or height, you have to set allow_zoom to true, otherwise the generated image will be smaller than required. If 'exact_ratio_with_borders', the image will have the exact specified width and height, and some borders may be added. The border color is the one specified by 'background_color'. If 'none' or missing, the image ratio is preserved, and depending od this ratio, may not have the exact width and height required.    |    Text (Default : "exact_ratio_with_borders") |
| rotation | The rotation angle in degrees (positive or negative) applied to the image. From -180 to 180. The background color of the empty areas is the one specified by 'background_color'    |    Integer (Default : 0) |
| persist | Persist on destination path after the processing     |    Integer (Default : 1) |
| allow_zoom | If true, the factory il allowed to resize an image to match the required width and height, causing, in most cases, a quality loss. If false, the image will never be zoomed.     |    Integer (Default : 0) |
| imagine_library_code | Name of the graphic driver used by the Imagine library (see https://imagine.readthedocs.org)     |    Integer (Default : "gd", Possible : "gd, imagick, gmagick") |

#### Todo

[ ] The configuration interface on the back office

