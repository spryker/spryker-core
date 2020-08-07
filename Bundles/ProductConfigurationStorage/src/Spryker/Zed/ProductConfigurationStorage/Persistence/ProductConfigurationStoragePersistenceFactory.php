<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage\Persistence;

use Orm\Zed\ProductConfigurationStorage\Persistence\SpyProductConfigurationStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductConfigurationStorage\Persistence\Propel\Mapper\ProductConfigurationStorageMapper;
use Spryker\Zed\ProductConfigurationStorage\Persistence\Propel\Mapper\ProductConfigurationStorageMapperInterface;

/**
 * @method \Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductConfigurationStorage\ProductConfigurationStorageConfig getConfig()
 */
class ProductConfigurationStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductConfigurationStorage\Persistence\SpyProductConfigurationStorageQuery
     */
    public function createProductConfigurationStorageQuery(): SpyProductConfigurationStorageQuery
    {
        return SpyProductConfigurationStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductConfigurationStorage\Persistence\Propel\Mapper\ProductConfigurationStorageMapperInterface
     */
    public function createProductConfigurationStorageMapper(): ProductConfigurationStorageMapperInterface
    {
        return new ProductConfigurationStorageMapper();
    }
}
