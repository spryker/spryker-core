<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeStorage\Persistence;

use Orm\Zed\ShipmentTypeStorage\Persistence\SpyShipmentTypeListStorageQuery;
use Orm\Zed\ShipmentTypeStorage\Persistence\SpyShipmentTypeStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToPropelFacadeInterface;
use Spryker\Zed\ShipmentTypeStorage\ShipmentTypeStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ShipmentTypeStorage\ShipmentTypeStorageConfig getConfig()
 * @method \Spryker\Zed\ShipmentTypeStorage\Persistence\ShipmentTypeStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ShipmentTypeStorage\Persistence\ShipmentTypeStorageEntityManagerInterface getEntityManager()
 */
class ShipmentTypeStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ShipmentTypeStorage\Persistence\SpyShipmentTypeStorageQuery
     */
    public function createShipmentTypeStorageQuery(): SpyShipmentTypeStorageQuery
    {
        return SpyShipmentTypeStorageQuery::create();
    }

    /**
     * In the current major version, this class can not be present. Depends on migration.
     *
     * @return \Orm\Zed\ShipmentTypeStorage\Persistence\SpyShipmentTypeListStorageQuery
     */
    public function createShipmentTypeListStorageQuery(): SpyShipmentTypeListStorageQuery
    {
        return SpyShipmentTypeListStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToPropelFacadeInterface
     */
    public function getPropelFacade(): ShipmentTypeStorageToPropelFacadeInterface
    {
        return $this->getProvidedDependency(ShipmentTypeStorageDependencyProvider::FACADE_PROPEL);
    }
}
