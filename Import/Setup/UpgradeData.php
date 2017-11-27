<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module
 * to newer versions in the future.
 */
namespace Smile\Pimgento\Import\Setup;

use Magento\Framework\App\Config\ReinitableConfigInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\DB\FieldDataConverterFactory;
use Magento\Framework\DB\FieldDataConverter;
use Magento\Framework\DB\Select\QueryModifierFactory;
use Magento\Framework\DB\Query\Generator as QueryGenerator;
use Magento\Framework\DB\DataConverter\SerializedToJson;
use Pimgento\Import\Helper\Config;

/**
 * Upgrade Data
 *
 * @author    David Dattee <dadat@smile.fr>
 * @copyright 2017 Smile
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var FieldDataConverterFactory
     */
    protected $fieldDataConverterFactory;

    /**
     * @var QueryModifierFactory
     */
    protected $queryModifierFactory;

    /**
     * @var \Magento\Framework\DB\Query\Generator
     */
    protected $queryGenerator;
    /**
     * @var ReinitableConfigInterface
     */
    private $reinitConfig;

    /**
     * Constructor
     *
     * @param FieldDataConverterFactory $fieldDataConverterFactory
     * @param QueryModifierFactory      $queryModifierFactory
     * @param QueryGenerator            $queryGenerator
     * @param ReinitableConfigInterface $reinitConfig
     */
    public function __construct(
        FieldDataConverterFactory $fieldDataConverterFactory,
        QueryModifierFactory      $queryModifierFactory,
        QueryGenerator            $queryGenerator,
        ReinitableConfigInterface $reinitConfig
    ) {
        $this->fieldDataConverterFactory = $fieldDataConverterFactory;
        $this->queryModifierFactory      = $queryModifierFactory;
        $this->queryGenerator            = $queryGenerator;
        $this->reinitConfig              = $reinitConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();

        /** Magento 2.2 Compatibility */
        if (version_compare($context->getVersion(), '1.1.0', '<')) {
            $this->convertSerializedDataToJson($setup);
        }

        $setup->endSetup();
    }

    /**
     * Convert the values from php serialize to json.
     *
     * @param ModuleDataSetupInterface $setup
     *
     * @return void
     */
    protected function convertSerializedDataToJson(ModuleDataSetupInterface $setup)
    {
        /** @var FieldDataConverter $fieldDataConverter */
        $fieldDataConverter = $this->fieldDataConverterFactory->create(SerializedToJson::class);

        $queryModifier = $this->queryModifierFactory->create(
            'in',
            [
                'values' => [
                    'path' => [
                        Config::PIMGENTO_CONFIG_IMPORT_DIR_KEY,
                        Config::PIMGENTO_CONFIG_WEBSITE_MAPPING_KEY
                    ]
                ]
            ]
        );

        $fieldDataConverter->convert(
            $setup->getConnection(),
            $setup->getTable('core_config_data'),
            'config_id',
            'value',
            $queryModifier
        );

        $this->reinitConfig->reinit();
    }
}