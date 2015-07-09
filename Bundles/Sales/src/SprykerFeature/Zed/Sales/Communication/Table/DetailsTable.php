<?php
namespace SprykerFeature\Zed\Sales\Communication\Table;

use SprykerFeature\Zed\Gui\Communication\Table\BaseTable;
use SprykerFeature\Zed\Gui\Communication\Table\BaseTableConfiguration;

class DetailsTable extends BaseTable
{

    /**
     * @param $data
     */
    public function prepareDate($data)
    {

        $this->loadObjectCollection($data);
        $config = new BaseTableConfiguration();
        $config->setHeaders([
            'header1' => 'First header'
        ]);
        $config->setSortable(['header1']);
        $config->setPageLength(2);
        $this->setConfiguration($config);

    }

    protected function configure(TableConfiguration $config)
    {
    }

    protected function prepareData(TableConfiguration $config)
    {
    }
}
