# PIMGento

![alt text][logo]
[logo]: http://i.imgur.com/q0sdWSs.png "PIMGento : "

PIMGento is a Magento 2 extension that allows you to import your catalog from Akeneo CSV files into Magento.

## How it works

PIMGento reads CSV files from Akeneo and insert data directly in Magento database.

In this way, it makes imports very fast and doesn't disturb your e-commerce website.

With PIMGento, you can import :
* Categories
* Families
* Attributes
* Options
* Variants (configurable products)
* Products

## Requirements

* Akeneo 1.3, 1.4 and 1.5
* Akeneo Bundle [EnhancedConnectorBundle](https://github.com/akeneo-labs/EnhancedConnectorBundle/)
* Magento >= 2.0 CE & EE (Magento EE 2.1 without Staging module)
* Set local_infile mysql variable to TRUE
* Database encoding must be UTF-8
* Add "driver_options" key to Magento default connection configuration (app/etc/env.php)

```php
'db' =>
  array (
    'table_prefix' => '',
    'connection' =>
    array (
      'default' =>
      array (
        'host' => '',
        'dbname' => '',
        'username' => '',
        'password' => '',
        'active' => '1',
        'driver_options' => array(PDO::MYSQL_ATTR_LOCAL_INFILE => true),
      ),
    ),
  ),
```

You need to install this Akeneo Bundle (https://github.com/akeneo-labs/EnhancedConnectorBundle/)
in order to generate appropriate CSV files for Magento.

### Installation ###

Install module by Composer as follows:

```shell
composer require agencednd/module-pimgento
```

Enable and install module(s) in Magento:

```shell
# [Required] Import tools
php bin/magento module:enable Pimgento_Import

# [Required] Database features
php bin/magento module:enable Pimgento_Entities

# [Optional] Database logs (System > Pimgento > Log)
php bin/magento module:enable Pimgento_Log

# [Optional] Activate desired imports
php bin/magento module:enable Pimgento_Category
php bin/magento module:enable Pimgento_Family
php bin/magento module:enable Pimgento_Attribute
php bin/magento module:enable Pimgento_Option
php bin/magento module:enable Pimgento_Variant
php bin/magento module:enable Pimgento_Product
```

Check and update database setup:
```shell
php bin/magento setup:db:status
php bin/magento setup:upgrade
```

Flush Magento caches
```shell
php bin/magento cache:flush
```

## Configuration and Usage

* Configure your store language and currency before import
* Launch import from admin panel in "System > Pimgento > Import"
* After category import, set the "Root Category" for store in "Stores > Settings > All Stores"

## Command line

Launch import with command line:

```shell
php bin/magento pimgento:import --code=product --file=product.csv
```

## Media import

The media files are imported during the simple product import process.
They must be in a folder *files* in the same folder of the simple product csv file.
You can configure the columns to use in the Magento Catalog Pimgento configuration section.
The value must be exactly the path of the image, relatively to the csv file: files/foo/bar.png

## Staging support
Pimgento is compatible with Magento EE Staging. It has 4 modes for updating products. 

Categories are using the "Update Last Created Stage" mode and can't be configured for now. 

### Staging Modes

#### Update Last Created Stage
In this mode pimgento will simply update the last version of the product that exists. 
It won't change any other versions.

#### Update Current Stage
In this mode pimgento will simply update the current version of the product.
It won't change any other versions.

#### Update All Stages
In this mode Pimgento will update all the version/stages of the products. 

You should use this mode if for example you are handling prices throught the staging in Magento but get the products information from your PIM and don't wish to have different product information on different stages.

#### Full mode 
In this mode you need to have in your product csv file the additional fields `from` and `to`. Those columns needs to be dates in the fallowing format : `YYYY-MM-DD` 

Those fields are going to be used to create new stages for the products during the import. There is a few points you need to look for : 
* You can't update or create stages that has already started. 
* You can't have 2 stages starting the same day but finishing on a different day (Magento handles this by simply adding a second to the beginning of the stage but pimgento won't handle it.)
* You can't have different stages on simple products of the same configurable. 
* You can't edit existing stages. 
  * If you created a stage from 2016-01-20 to the infinity and try to import a new version that starts at the same date it won't allow it. Basically you won't be allowed to create any new stage on this product.
  * If you created a stage from 2016-01-20 to 2017-01-20 you won't be able to create a stage in between. 


## Roadmap

* Pim code exclusion
* Improve Magento EE 2.1 Staging support on categories like it was done on products.
* Improve version date checks during staging full import. Currently some import dates brakes the DB.

## About us

Founded by lovers of innovation and design, [Agence Dn'D] (http://www.dnd.fr) assists companies for 11 years in the creation and development of customized digital (open source) solutions for web and E-commerce.
