<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Persistence;

interface CountryQueryContainerInterface
{

    /**
     * @api
     *
     * @return \Orm\Zed\Country\Persistence\SpyCountryQuery
     */
    public function queryCountries();

    /**
     * @api
     *
     * @param string $iso2Code
     *
     * @return \Orm\Zed\Country\Persistence\SpyCountryQuery
     */
    public function queryCountryByIso2Code($iso2Code);

    /**
     * @api
     *
     * @return \Orm\Zed\Country\Persistence\SpyRegionQuery
     */
    public function queryRegions();

    /**
     * @api
     *
     * @param string $isoCode
     *
     * @return \Orm\Zed\Country\Persistence\SpyRegionQuery
     */
    public function queryRegionByIsoCode($isoCode);

}
