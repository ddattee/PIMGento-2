PIMGento2 Cron
==============

About it:
---------

You can configure crontask through configuration for each import. You just need:

*  Set your cron expression in your crontab following this exemple:
```shell
0 22 * * * /usr/bin/php /path/to/magento/current/bin/magento pimgento:import --code=import-type --file=filename.csv >> /path/to/magento/current/var/log/pimgento_import_type.cron.log`
```


Technical stuff you should know about:
--------------------------------------

## Command line

you can also launch import with command line:

```shell
php bin/magento pimgento:import --code=product --file=product.csv
```

