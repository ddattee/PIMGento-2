<?php


namespace Pimgento\Staging\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Framework\App\Helper\Context;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use Magento\Staging\Model\VersionManager;
use Pimgento\Entities\Model\Entities;

/**
 * Class to help import products & categories with stage module enabled.
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package Pimgento\Staging\Helper
 */
class Import extends AbstractHelper
{
    /**
     * @var Config
     */
    protected $configHelper;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param Config $configHelper
     */
    public function __construct(Context $context, \Pimgento\Staging\Helper\Config $configHelper)
    {
        $this->configHelper = $configHelper;

        parent::__construct($context);
    }

    /**
     * Add required data columns on the temporary table.
     *
     * @param AdapterInterface $connection
     * @param $tmpTable
     */
    public function addRequiredData(AdapterInterface $connection, $tmpTable)
    {
        if ($this->configHelper->isCatalogStagingModulesEnabled()) {
            $connection->addColumn($tmpTable, '_row_id', 'INT(11)');
            $connection->addColumn($tmpTable, 'created_in', 'INT(11)');
            $connection->addColumn($tmpTable, 'updated_in', 'INT(11)');
        }
    }

    /**
     * Matching the row id's for all our entities.
     *
     * @param Entities $entities
     * @param string $entityTableCode
     * @param string $code
     * @param string $stagingMode
     */
    public function matchEntityRows(Entities $entities, $entityTableCode, $code, $stagingMode)
    {
        $connection = $entities->getResource()->getConnection();
        $tmpTable = $entities->getTableName($code);
        $entityTable = $entities->getResource()->getTable($entityTableCode);

        $startTime = time();

        switch ($stagingMode) {
            case Config::STAGING_MODE_LAST:
            case Config::STAGING_MODE_ALL:
                /**
                 * Update row id column.
                 * We are going to update the last version that was created if there is multiple versions
                 *
                 * In full mode we will have another step that will duplicate the content in all the stages.
                 */
                $connection->query(
                    'UPDATE `' . $tmpTable . '` t
                    SET `_row_id` = (
                        SELECT MAX(row_id) FROM  `' . $entityTable . '` c
                        WHERE c.entity_id = t._entity_id
                    )'
                );

                break;

            case Config::STAGING_MODE_CURRENT:
                /**
                 * Update row id column.
                 * We are going to update the current versions.
                 */
                $connection->query(
                    'UPDATE `' . $tmpTable . '` t
                    SET `_row_id` = (
                        SELECT MAX(row_id) FROM  `' . $entityTable . '` c
                        WHERE c.entity_id = t._entity_id 
                            AND ( ' . $startTime . ' BETWEEN created_in AND updated_in)
                    )'
                );

                break;
        }


        /**
         * For existing versions fetch version created_in & updated_in from database.
         */
        $connection->query(
            'UPDATE `' . $tmpTable . '` t
            INNER JOIN  `' . $entityTable . '` c ON c.row_id = t._row_id
            SET t.created_in = c.created_in, 
                t.updated_in = c.updated_in
            WHERE t.created_in is NULL'
        );

        /**
         * For new entities we need to put default created_in & updated_in values.
         */
        $connection->query(
            'UPDATE `' . $tmpTable . '` t
            SET `created_in` = 1, 
                `updated_in` = ' . VersionManager::MAX_VERSION . '
            WHERE t.created_in is NULL'
        );
    }

    /**
     * When staging mode is enabled we also need to fill up the sequence_product table.
     *
     * @param AdapterInterface $connection
     * @param string $sequenceTable
     * @param string $tmpTable
     */
    public function createEntitiesBefore(AdapterInterface $connection, $sequenceTable, $tmpTable)
    {
        if ($this->configHelper->isCatalogStagingModulesEnabled()) {
            $sequenceValues = ['sequence_value' => '_entity_id'];
            $parents = $connection->select()->from($tmpTable, $sequenceValues);
            $connection->query(
                $connection->insertFromSelect(
                    $parents,
                    $connection->getTableName($sequenceTable),
                    array_keys($sequenceValues),
                    1
                )
            );
        }
    }

    /**
     * Once the catalog_entity table has been filled, we need to get the row id's for all the new
     * versions so that we can insert the values after.
     *
     * @param AdapterInterface $connection
     * @param string $entityTableCode
     * @param string $tmpTable
     */
    public function createEntitiesAfter(AdapterInterface $connection, $entityTableCode, $tmpTable)
    {
        if ($this->configHelper->isCatalogStagingModulesEnabled()) {
            $connection->query(
                'UPDATE `' . $tmpTable . '` t
                SET `_row_id` = (
                    SELECT MAX(row_id) FROM  `' . $connection->getTableName($entityTableCode) . '` c
                    WHERE c.entity_id = t._entity_id
                )
                WHERE t._row_id IS NULL'
            );
        }
    }

    /**
     * @param AdapterInterface $connection
     * @param string $entityTable
     * @param string $tmpTable
     *
     * @return Select
     */
    public function getBaseStageDuplicationSelect(AdapterInterface $connection, $entityTable, $tmpTable)
    {
        return $connection->select()
            ->from(
                ['e' =>$entityTable],
                []
            )->joinInner(
                ['t' => $tmpTable],
                't._entity_id = e.entity_id AND t._row_id != e.row_id',
                []
            )->setPart('disable_staging_preview', true);
    }
}