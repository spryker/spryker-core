<?php
namespace SprykerFeature\Zed\Country\Communication\Table;

use SprykerFeature\Zed\Country\Persistence\Propel\SpyCountryQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class CountryTable extends AbstractTable
{

    /**
     * @var SpyCountryQuery
     */
    protected $countryQuery;

    /**
     * @param SpyCountryQuery $countryQuery
     */
    public function __construct(SpyCountryQuery $countryQuery)
    {
        $this->countryQuery = $countryQuery;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeaders(
            [
                'iso2_code' => 'ISO2 code',
                'iso3_code' => 'ISO3 Code',
                'name'  => 'Country name'
            ]
        );
        $config->setSortable(['header1']);

        return $config;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return \Propel\Runtime\Collection\ObjectCollection
     */
    protected function prepareData(TableConfiguration $config)
    {
        return $this->runQuery($this->countryQuery, $config);

    }
}
