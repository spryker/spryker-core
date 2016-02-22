<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
