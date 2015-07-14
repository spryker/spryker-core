<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Country\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Country\Persistence\Propel\SpyCountryQuery;
use SprykerFeature\Zed\Country\Persistence\Propel\SpyRegionQuery;

class CountryQueryContainer extends AbstractQueryContainer implements CountryQueryContainerInterface
{

    /**
     * @return SpyCountryQuery
     */
    public function queryCountries()
    {
        return SpyCountryQuery::create();
    }

    /**
     * @param string $iso2Code
     *
     * @return SpyCountryQuery
     */
    public function queryCountryByIso2Code($iso2Code)
    {
        $query = $this->queryCountries();
        $query
            ->filterByIso2Code($iso2Code);

        return $query;
    }

    /**
     * @return SpyRegionQuery
     */
    public function queryRegions()
    {
        return SpyRegionQuery::create();
    }

    /**
     * @param string $isoCode
     *
     * @return SpyRegionQuery
     */
    public function queryRegionByIsoCode($isoCode)
    {
        $query = $this->queryRegions();
        $query
            ->filterByIso2Code($isoCode);

        return $query;
    }

}
