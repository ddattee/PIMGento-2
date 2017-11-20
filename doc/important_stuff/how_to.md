How to...
========
##### Q: How to install PIMGento2 ?
**A**: You can install PIMGento2 with composer:

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

#### Q: How to configure PIMGento2 ?
**A**: Before starting to use PIMGento2, few steps are require to set it right:
* Configure your store language and currency before import
* Launch import from admin panel in "System > Pimgento > Import"
* After category import, set the "Root Category" for store in "Stores > Settings > All Stores"
...and you are good to go! Just check the configuration to be ready to import your data the right way!

#### Q: How to import my data into PIMGento2 ?
**A**: You can import your data using two differents ways:
* Using the [interface](../functionnalities/pimgento_interface.md)
* Using [cron tasks](../functionnalities/pimgento_cron.md)

But before using one of these methods be sure to read this [quick guide](../functionnalities/pimgento_import.md) about the import system.

#### Q: How to customize PIMGento2 ?
**A**: If even the multiple configuration of PIMGento2 doesn't suit your business logic, or if you want to have other possibilities in import, you can always override PIMGento2 as it is completly Open Source. Just keep in mind a few things before beginning to develop your own logic:
* Observers define each task for a given import, if you want to add a task you should declaring a new method in the corresponding Import class and adding to the Observer.
* One method in Import class = One task
* There is no data transfer between tasks

Note that if you judge your feature can be used by others, and if you respect this logic, we will be glad to add it to PIMGento2: just make us a PR!

#### Q: How to contribute to PIMGento2 ?
**A**: You can contribute to PIMGento2 by submitting PR on Github. However, you need to respect a few criteria:
* Respect PIMGento2 logic and architecture
* Be sure to not break others features
* Submit a clean code
* Always update the documentation if you submit a new feature
