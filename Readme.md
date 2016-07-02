# Image Factory

This module is in development, the readme will be completed later
Thank you for your understanding

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is ImageFactory.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/image-factory-module:~0.3.0
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
