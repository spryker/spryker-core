<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Country\Persistence\CountryPersistenceFactory getFactory()
 */
class CountryQueryContainer extends AbstractQueryContainer implements CountryQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Country\Persistence\SpyCountryQuery
     */
    public function queryCountries()
    {
        return $this->getFactory()->createCountryQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $iso3Code
     *
     * @return \Orm\Zed\Country\Persistence\SpyCountryQuery
     */
    public function queryCountryByIso3Code($iso3Code)
    {
        $query = $this->queryCountries();
        $query
            ->filterByIso3Code($iso3Code);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Country\Persistence\SpyRegionQuery
     */
    public function queryRegions()
    {
        return $this->getFactory()->createRegionQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
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
