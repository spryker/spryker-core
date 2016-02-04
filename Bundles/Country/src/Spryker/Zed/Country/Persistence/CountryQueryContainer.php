<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Country\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Country\Persistence\SpyRegionQuery;

/**
 * @method CountryPersistenceFactory getFactory()
 */
class CountryQueryContainer extends AbstractQueryContainer implements CountryQueryContainerInterface
{

    /**
     * @return \Orm\Zed\Country\Persistence\SpyCountryQuery
     */
    public function queryCountries()
    {
        return $this->getFactory()->createCountryQuery();
    }

    /**
     * @param string $iso2Code
     *
     * @return \Orm\Zed\Country\Persistence\SpyCountryQuery
     */
    public function queryCountryByIso2Code($iso2Code)
    {
        $query = $this->queryCountries();
        $query
            ->filterByIso2Code($iso2Code);

        return $query;
    }

    /**
     * @return \Orm\Zed\Country\Persistence\SpyRegionQuery
     */
    public function queryRegions()
    {
        return $this->getFactory()->createRegionQuery();
    }

    /**
     * @param string $isoCode
     *
     * @return \Orm\Zed\Country\Persistence\SpyRegionQuery
     */
    public function queryRegionByIsoCode($isoCode)
    {
        $query = $this->queryRegions();
        $query
            ->filterByIso2Code($isoCode);

        return $query;
    }

}
