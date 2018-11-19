<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Persistence;

use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Country\Persistence\SpyRegionQuery;
use Spryker\Zed\Country\Persistence\Propel\Mapper\CountryMapper;
use Spryker\Zed\Country\Persistence\Propel\Mapper\CountryMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Country\CountryConfig getConfig()
 * @method \Spryker\Zed\Country\Persistence\CountryQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Country\Persistence\CountryRepositoryInterface getRepository()
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

    /**
     * @return \Spryker\Zed\Country\Persistence\Propel\Mapper\CountryMapperInterface
     */
    public function createCountryMapper(): CountryMapperInterface
    {
        return new CountryMapper();
    }
}
