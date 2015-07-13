<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Country\Communication\Table;

use Propel\Runtime\Collection\ObjectCollection;
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
        $config->setHeaders([
            'Iso2Code' => 'ISO2 code',
            'Iso3Code' => 'ISO3 Code',
            'Name'  => 'Country name'
        ]);
        $config->setSortable([
            'Iso3Code'
        ]);

        return $config;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return ObjectCollection
     */
    protected function prepareData(TableConfiguration $config)
    {
        return $this->runQuery($this->countryQuery, $config);
    }
}
