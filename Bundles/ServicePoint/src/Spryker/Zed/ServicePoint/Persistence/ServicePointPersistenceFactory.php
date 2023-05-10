<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Persistence;

use Orm\Zed\ServicePoint\Persistence\SpyServicePointAddressQuery;
use Orm\Zed\ServicePoint\Persistence\SpyServicePointQuery;
use Orm\Zed\ServicePoint\Persistence\SpyServicePointServiceQuery;
use Orm\Zed\ServicePoint\Persistence\SpyServicePointStoreQuery;
use Orm\Zed\ServicePoint\Persistence\SpyServiceTypeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ServicePoint\Persistence\Propel\Mapper\CountryMapper;
use Spryker\Zed\ServicePoint\Persistence\Propel\Mapper\ServicePointAddressMapper;
use Spryker\Zed\ServicePoint\Persistence\Propel\Mapper\ServicePointMapper;
use Spryker\Zed\ServicePoint\Persistence\Propel\Mapper\ServicePointServiceMapper;
use Spryker\Zed\ServicePoint\Persistence\Propel\Mapper\ServiceTypeMapper;

/**
 * @method \Spryker\Zed\ServicePoint\ServicePointConfig getConfig()
 * @method \Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface getRepository()
 * @method \Spryker\Zed\ServicePoint\Persistence\ServicePointEntityManagerInterface getEntityManager()
 */
class ServicePointPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServicePointQuery
     */
    public function getServicePointQuery(): SpyServicePointQuery
    {
        return SpyServicePointQuery::create();
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServicePointStoreQuery
     */
    public function getServicePointStoreQuery(): SpyServicePointStoreQuery
    {
        return SpyServicePointStoreQuery::create();
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServicePointAddressQuery
     */
    public function getServicePointAddressQuery(): SpyServicePointAddressQuery
    {
        return SpyServicePointAddressQuery::create();
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServicePointServiceQuery
     */
    public function getServicePointServiceQuery(): SpyServicePointServiceQuery
    {
        return SpyServicePointServiceQuery::create();
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServiceTypeQuery
     */
    public function getServiceTypeQuery(): SpyServiceTypeQuery
    {
        return SpyServiceTypeQuery::create();
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Persistence\Propel\Mapper\ServicePointMapper
     */
    public function createServicePointMapper(): ServicePointMapper
    {
        return new ServicePointMapper(
            $this->createServicePointAddressMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Persistence\Propel\Mapper\ServicePointAddressMapper
     */
    public function createServicePointAddressMapper(): ServicePointAddressMapper
    {
        return new ServicePointAddressMapper(
            $this->createCountryMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Persistence\Propel\Mapper\CountryMapper
     */
    public function createCountryMapper(): CountryMapper
    {
        return new CountryMapper();
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Persistence\Propel\Mapper\ServicePointServiceMapper
     */
    public function createServicePointServiceMapper(): ServicePointServiceMapper
    {
        return new ServicePointServiceMapper();
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Persistence\Propel\Mapper\ServiceTypeMapper
     */
    public function createServiceTypeMapper(): ServiceTypeMapper
    {
        return new ServiceTypeMapper();
    }
}
