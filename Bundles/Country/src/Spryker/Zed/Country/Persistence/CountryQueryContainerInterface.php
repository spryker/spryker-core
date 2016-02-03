<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Country\Persistence;

interface CountryQueryContainerInterface
{

    /**
     * @return \Orm\Zed\Country\Persistence\SpyCountryQuery
     */
    public function queryCountries();

    /**
     * @param string $iso2Code
     *
     * @return \Orm\Zed\Country\Persistence\SpyCountryQuery
     */
    public function queryCountryByIso2Code($iso2Code);

    /**
     * @return \Orm\Zed\Country\Persistence\SpyRegionQuery
     */
    public function queryRegions();

    /**
     * @param string $isoCode
     *
     * @return \Orm\Zed\Country\Persistence\SpyRegionQuery
     */
    public function queryRegionByIsoCode($isoCode);

}
