<?php

namespace Pimgento\Entities\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;

class Config extends AbstractHelper
{
    /** Config keys */
    const CONFIG_PIMGENTO_EOL              = 'pimgento/general/lines_terminated';
    const CONFIG_PIMGENTO_EOF              = 'pimgento/general/fields_terminated';
    const CONFIG_PIMGENTO_ENCLOSURE        = 'pimgento/general/fields_enclosure';
    const CONFIG_PIMGENTO_LOCAL_DATA       = 'pimgento/general/load_data_local';
    const CONFIG_PIMGENTO_INSERTION_METHOD = 'pimgento/general/data_insertion_method';
    const CONFIG_PIMGENTO_QUERY_NB         = 'pimgento/general/query_number';

    /**
     * Data in file insertion method
     */
    const INSERTION_METHOD_DATA_IN_FILE = 'data_in_file';

    /**
     * By rows insertion method
     */
    const INSERTION_METHOD_BY_ROWS = 'by_rows';

    /**
     * Retrieve CSV configuration
     *
     * @return array
     */
    public function getCsvConfig()
    {
        return array(
            'lines_terminated'  => $this->scopeConfig->getValue(self::CONFIG_PIMGENTO_EOL),
            'fields_terminated' => $this->scopeConfig->getValue(self::CONFIG_PIMGENTO_EOF),
            'fields_enclosure'  => $this->scopeConfig->getValue(self::CONFIG_PIMGENTO_ENCLOSURE),
        );
    }

    /**
     * Retrieve Load Data Infile option
     *
     * @return int
     */
    public function getLoadDataLocal()
    {
        return $this->scopeConfig->getValue(self::CONFIG_PIMGENTO_LOCAL_DATA);
    }

    /**
     * Retrieve insertion method
     *
     * @return string
     */
    public function getInsertionMethod()
    {
        return (string) $this->scopeConfig->getValue(self::CONFIG_PIMGENTO_INSERTION_METHOD);
    }

    /**
     * Retrieve query number for multiple insert
     *
     * @return int
     */
    public function getQueryNumber()
    {
        return (int) $this->scopeConfig->getValue(self::CONFIG_PIMGENTO_QUERY_NB);
    }
}