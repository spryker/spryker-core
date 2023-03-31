<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Persistence;

use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Country\Persistence\SpyCountryStoreQuery;
use Orm\Zed\Country\Persistence\SpyRegionQuery;
use Spryker\Zed\Country\Persistence\Propel\Mapper\CountryMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Country\CountryConfig getConfig()
 * @method \Spryker\Zed\Country\Persistence\CountryRepositoryInterface getRepository()
 * @method \Spryker\Zed\Country\Persistence\CountryEntityManagerInterface getEntityManager()
 */
class CountryPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Country\Persistence\SpyCountryQuery<mixed>
     */
    public function createCountryQuery(): SpyCountryQuery
    {
        return SpyCountryQuery::create();
    }

    /**
     * @return \Orm\Zed\Country\Persistence\SpyRegionQuery<mixed>
     */
    public function createRegionQuery(): SpyRegionQuery
    {
        return SpyRegionQuery::create();
    }

    /**
     * @return \Spryker\Zed\Country\Persistence\Propel\Mapper\CountryMapper
     */
    public function createCountryMapper(): CountryMapper
    {
        return new CountryMapper();
    }

    /**
     * @return \Orm\Zed\Country\Persistence\SpyCountryStoreQuery<mixed>
     */
    public function createCountryStorePropelQuery(): SpyCountryStoreQuery
    {
        return SpyCountryStoreQuery::create();
    }
}
