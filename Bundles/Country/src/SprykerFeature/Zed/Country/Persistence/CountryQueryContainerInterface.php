<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Country\Persistence;

use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Country\Persistence\SpyRegionQuery;

interface CountryQueryContainerInterface
{

    /**
     * @return SpyCountryQuery
     */
    public function queryCountries();

    /**
     * @param string $iso2Code
     *
     * @return SpyCountryQuery
     */
    public function queryCountryByIso2Code($iso2Code);

    /**
     * @return SpyRegionQuery
     */
    public function queryRegions();

    /**
     * @param string $isoCode
     *
     * @return SpyRegionQuery
     */
    public function queryRegionByIsoCode($isoCode);

}
