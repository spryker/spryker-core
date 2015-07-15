<?php
namespace SprykerFeature\Zed\Country\Communication\Table;

use SprykerFeature\Zed\Country\Persistence\Propel\SpyCountryQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class DetailsTable extends AbstractTable
{

    /**
     * @var SpyCountryQuery
     */
    protected $countryQuery;

    public function __construct(SpyCountryQuery $countryQuery)
    {
        $this->countryQuery = $countryQuery;
    }

    protected function configure(TableConfiguration $config)
    {
        $config->setHeaders(
            [
                'header1' => 'First header'
            ]);

        $config->setSortable(['header1']);

        return $config;
    }

    /**
     *
     */
    protected function prepareData(TableConfiguration $config)
    {
        return $this->runQuery($this->countryQuery, $config);
//        return [
//            ['header1' => 'aaa'],
//            ['header1' => 'bbb']
//        ];
    }


}
