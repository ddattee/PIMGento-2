<?php

namespace Pimgento\Product\Model\Source;

/**
 * Class To display different stating modes in the BO.
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package Pimgento\Product\Model\Source
 */
class StagingMode implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $configHelper;

    /**
     * PHP Constructor to get the module manager to be able to display proper options.
     *
     * @param \Pimgento\Product\Helper\Config $configHelper
     */
    public function __construct(
        \Pimgento\Product\Helper\Config $configHelper
    ) {
        $this->configHelper = $configHelper;
    }


    /**
     * Retrieve Insertion method Option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            ['value' => \Pimgento\Product\Helper\Config::STAGING_MODE_SIMPLE, 'label' => __('Simple')],
        ];

        // Allow full staging mode only if the catalog staging modules are enabled.
        if ($this->configHelper->isCatalogStagingModulesEnabled()) {
            $options[] = ['value' => \Pimgento\Product\Helper\Config::STAGING_MODE_FULL, 'label' => __('Full')];
        }

        return $options;
    }
}