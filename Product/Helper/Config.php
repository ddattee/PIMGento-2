<?php

namespace Pimgento\Product\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Framework\App\Helper\Context;
use \Magento\Store\Model\StoreManagerInterface;
use \Magento\Framework\Filesystem;

class Config extends AbstractHelper
{
    /**
     * Constants to configuration paths.
     */
    const CONFIG_PATH_STAGING_MODE = 'pimgento/product/staging_mode';

    const STAGING_MODE_SIMPLE = 'simple';
    const STAGING_MODE_FULL = 'full';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        \Magento\Framework\Module\Manager $moduleManager
    ) {
        $this->_storeManager = $storeManager;
        $this->moduleManager = $moduleManager;

        parent::__construct($context);
    }

    /**
     * Retrieve stores default tax class
     *
     * @return array
     */
    public function getProductTaxClasses()
    {
        $classes = $this->scopeConfig->getValue('pimgento/product/tax_class');

        $result = array();

        $stores = $this->_storeManager->getStores(true);

        if ($classes) {
            $classes = unserialize($classes);
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

        if (!$this->isCatalogStagingModulesEnabled()) {
            return self::STAGING_MODE_SIMPLE;
        } else {
            return $this->scopeConfig->getValue(self::CONFIG_PATH_STAGING_MODE);
        }
    }

    /**
     * Check if staging module is enabled for the catalog.
     *
     * @return bool
     */
    public function isCatalogStagingModulesEnabled()
    {
        return $this->moduleManager->isEnabled('Magento_Staging')
            && $this->moduleManager->isEnabled('Magento_CatalogStaging');
    }

    /**
     * Check if current configuration asks import to be in full staging mode or not.
     *
     * @return bool
     */
    public function isImportInFullStagingMode()
    {
        return $this->getImportStagingMode() == self::STAGING_MODE_FULL;
    }
}