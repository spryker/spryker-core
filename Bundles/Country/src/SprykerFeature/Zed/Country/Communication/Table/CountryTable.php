<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Country\Communication\Table;

use Propel\Runtime\Collection\ObjectCollection;
use Orm\Zed\Country\Persistence\Map\SpyCountryTableMap;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
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
        $config->setHeader([
            SpyCountryTableMap::COL_ISO2_CODE => 'ISO2 code',
            SpyCountryTableMap::COL_ISO3_CODE => 'ISO3 code',
            SpyCountryTableMap::COL_NAME => 'Country Name',
        ]);
        $config->setSortable([
            SpyCountryTableMap::COL_ISO3_CODE,
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
