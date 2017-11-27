<?php

namespace Pimgento\Product\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Framework\App\Helper\Context;
use \Magento\Store\Model\StoreManagerInterface;
use \Pimgento\Staging\Helper\Config as StagingConfigHelper;

class Config extends AbstractHelper
{
    /** Config keys */
    const CONFIG_PIMGENTO_PRODUCT_TAXCLASS          = 'pimgento/product/tax_class';
    const CONFIG_PIMGENTO_PRODUCT_ATTR_MAPPING      = 'pimgento/product/attribute_mapping';
    const CONFIG_PIMGENTO_PRODUCT_CONFIGURABLE_ATTR = 'pimgento/product/configurable_attributes';
    const CONFIG_PIMGENTO_IMG_ENABLED               = 'pimgento/image/enabled';
    const CONFIG_PIMGENTO_IMG_PATH                  = 'pimgento/image/path';
    const CONFIG_PIMGENTO_IMG_BASE                  = 'pimgento/image/base_image';
    const CONFIG_PIMGENTO_IMG_THUMB                 = 'pimgento/image/thumbnail_image';
    const CONFIG_PIMGENTO_IMG_SMALL                 = 'pimgento/image/small_image';
    const CONFIG_PIMGENTO_IMG_GALLERY               = 'pimgento/image/gallery_image';
    const CONFIG_PIMGENTO_IMG_SWATCH                = 'pimgento/image/swatch_image';
    const CONFIG_PIMGENTO_IMG_CLEAN_FILES           = 'pimgento/image/clean_files';

    /**
     * Constants to configuration profile.
     */
    const CONFIG_PROFILE = 'product';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Pimgento\Import\Helper\Config
     */
    protected $stagingConfigHelper;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        StagingConfigHelper $statingConfigHelper
    ) {
        $this->_storeManager = $storeManager;
        $this->stagingConfigHelper = $statingConfigHelper;

        parent::__construct($context);
    }

    /**
     * Retrieve stores default tax class
     *
     * @return array
     */
    public function getProductTaxClasses()
    {
        $classes = $this->scopeConfig->getValue(self::CONFIG_PIMGENTO_PRODUCT_TAXCLASS);

        $result = array();

        $stores = $this->_storeManager->getStores(true);

        if ($classes) {
            $classes = json_decode($classes);
            if (is_array($classes)) {
                foreach ($classes as $class) {

                    if ($this->getDefaultWebsiteId() == $class['website']) {
                        $result[0] = $class['tax_class'];
                    }

                    foreach ($stores as $store) {
                        if ($store->getWebsiteId() == $class['website']) {
                            $result[$store->getId()] = $class['tax_class'];
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Retrieve default website id
     *
     * @return int
     */
    public function getDefaultWebsiteId()
    {
        return $this->_storeManager->getStore()->getWebsiteId();
    }

    /**
     * Get import staging mode to use.
     *
     * @return mixed|string
     */
    public function getImportStagingMode()
    {

        return $this->stagingConfigHelper->getImportStagingMode(self::CONFIG_PROFILE);
    }

    /**
     * Check if current configuration asks import to be in full staging mode or not.
     *
     * @return bool
     */
    public function isImportInFullStagingMode()
    {
        return $this->getImportStagingMode() == StagingConfigHelper::STAGING_MODE_FULL;
    }
}