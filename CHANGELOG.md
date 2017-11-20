# Change Log

All notable changes to this project will be documented in this file.

## [100.2.23] - 2017-10-25

  * update: set query number for rows insertion in configuration
  * fix : avoid warning with website matching

## [100.2.22] - 2017-09-27

  * fix: Url Key localized

## [100.2.21] - 2017-09-25

  * fix: Variant table engine for big data
  * fix: Attribute attached to a lot of families

## [100.2.20] - 2017-09-05

  * update: disabled media importation if needed

## [100.2.19] - 2017-08-30

  * update: EnhancedConnectorBundle requirement is now useless
  * fix: remove product from old categories

## [100.2.18] - 2017-07-31

  * fix: import on table with prefix
  * fix: table engine with big data

## [100.2.17] - 2017-07-21

  * fix: notice on table variable

## [100.2.16] - 2017-07-20

  * fix: URL rewrite with ignored URL key

## [100.2.15] - 2017-07-18

  * fix: incompatibility with EE staging content
  * fix: url_key erased when category url key update is set to NO

## [100.2.14] - 2017-07-05

  * fix: event names
  * fix: static values for entity
  * fix: clean System Urls Before Insertion
  * fix: category URL key increment on multi-store

## [100.2.12] - 2017-07-04

  * fix: overwriting of existing category product positions when importing products
  * fix: set Magento value data to NULL for empty Pim value

## [100.2.11] - 2017-06-29

  * fix: Generate image cache
  * fix: localized, multiselect attribute import

## [100.2.10] - 2017-06-28

  * fix: area code in CLI
  * fix: data overwritten by last language
  * fix: url rewrite suffix for product and category

## [100.2.9] - 2017-06-22

  * add: New configuration for not erase category url key on update

## [100.2.8] - 2017-06-06

  * add: channel configuration for default values

## [100.2.7] - 2017-05-30

  * fix: avoid duplicate URL key

## [100.2.6] - 2017-05-24

  * fix: channel added in url_key column

## [100.2.5] - 2017-03-17

  * fix: fix image import with staging mode

## [100.2.4] - 2017-02-21

  * fix: Error during URL rewrite updates with multiple locales

## [100.2.3] - 2017-02-21

  * fix: Integrity constraint violation when importing in a shop with multiple store views

## [100.2.2] - 2017-02-21

  * fix: Avoid error on products without family

## [100.2.1] - 2017-02-21

  * fix: avoid duplicate URL keys for categories

## [100.2.0] - 2017-02-03

  * add: code refactoring for media and related
  * add: compatibility with staging modules

## [100.1.2] - 2017-02-01

  * fix: load model import
  * fix: Use the configured category suffix instead of hardcoded.html
  * fix: Open up PHP version constraints
  * add: refactoring all the "AddPimgentoImportObserver" observers to add generic events on classname and additionnal steps
  * add: add position to media during product import
  * fix: get url suffix from config for url rewrite
  * fix: composer version in composer.json file
  * fix: error on loading import model

## [100.1.1] - 2016-11-03

  WARNING: break compatibility on pimgento_attribute_get_specific_columns_add_after observer

  * fix: clean EAV cache on attribute import
  * fix: refactoring on attribute import
  * fix: issue #48 invalid website_id for stock_item for magento 2.1
  * fix: better performance for related product import (x4 faster)
  * fix: pb on url rewrite duplication row in url_rewrite table
  * add: new event `pimgento_attribute_get_specific_columns_add_after` that allows to add other columns for attribute definition
  * add: error message when import file with an invalid row (too many columns)
  * add: media import
  * add: can define the columns of attribute import that can be updated, or that can only be set on attribute creation

## [100.1.0] - 2016-07-04

  * Magento 2.1 compatibility

## [100.0.13] - 2016-06-30

  * fix: multi-select attribute options

## [100.0.12] - 2016-06-21

  * fix: Import of Related / Cross-Sell / UP-Sell

## [100.0.11] - 2016-06-21

  * fix: DataSetup Constructor argument error when di is generated
  * fix: use Magento2 factories to avoid bug when launching 2 imports during the same php execution
  * fix: Channel and language Inversion
  * add: Import of Related / Cross-Sell / UP-Sell for products
  * add: management of frontend_model on attribute import
  * add: events on attribute types
  * add: events on product import steps

## [100.0.10] - 2016-06-03

  * add: Website tax class configuration
  * fix: Duplicate label for attributes
  * fix: wrong value for attribute when unit association exists

## [100.0.9] - 2016-06-02
  
  * add: Some documentations (changelog, license, contributing)
  * add: Ability to use full path filename on setFile method, in order to import files that are not in the upload folder.
  * add: New config option Pimgento\General\data_insertion_method with 2 values : "Load Data Infile" and "By 1000 rows"
  * add: Default configurable attribute values configuration (Example: force status to enabled)

## [100.0.8] - 2016-05-20

  * add: Add virtual product support. You can use a column type_id in the product csv file, and using simple/virtual values
  * fix: Fix columns matching (all case) for configurable products
  * fix: Disable the demo module
  * fix: Better readme for compatibility with akeneo 1.5

## [100.0.7] - 2016-05-13

  * fix: Better attribute type mapping for price attribute
  * fix: Init stock infos for configurable product
  * fix: Do not create stock infos per website, but only on the main website.
  * fix: Bad translation
  * fix: Bad default price type

## [100.0.6] - 2016-04-29

  * add: Import directory configuration
  * add: French translation
  * add: Admin ACL
  * fix: Sample data

## [100.0.5] - 2016-04-22

  * add: Command line execution

## [100.0.4] - 2016-04-12

  * fix: Column duplication improvement

## [100.0.3] - 2016-04-12

  * fix: Allow multiple columns matching
  * fix: Match localizable and scopable columns

## [100.0.2] - 2016-04-08

  * add: LOAD DATA INFILE request configuration. You can add LOCAL option to request

## [100.0.1] - 2016-04-06

  * First version of PimGento2 for Magento 2