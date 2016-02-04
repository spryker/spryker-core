<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Country\Persistence;

use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Country\Persistence\SpyRegionQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Country\CountryConfig getConfig()
 * @method \Spryker\Zed\Country\Persistence\CountryQueryContainer getQueryContainer()
 */
class CountryPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\Country\Persistence\SpyCountryQuery
     */
    public function createCountryQuery()
    {
        return SpyCountryQuery::create();
    }

    /**
     * @return \Orm\Zed\Country\Persistence\SpyRegionQuery
     */
    public function createRegionQuery()
    {
        return SpyRegionQuery::create();
    }

}
