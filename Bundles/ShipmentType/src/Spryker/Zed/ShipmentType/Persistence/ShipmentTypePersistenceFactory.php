<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Persistence;

use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery;
use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeStoreQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ShipmentType\Persistence\Propel\Mapper\ShipmentTypeMapper;

/**
 * @method \Spryker\Zed\ShipmentType\ShipmentTypeConfig getConfig()
 * @method \Spryker\Zed\ShipmentType\Persistence\ShipmentTypeRepositoryInterface getRepository()
 * @method \Spryker\Zed\ShipmentType\Persistence\ShipmentTypeEntityManagerInterface getEntityManager()
 */
class ShipmentTypePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery
     */
    public function createShipmentTypeQuery(): SpyShipmentTypeQuery
    {
        return SpyShipmentTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeStoreQuery
     */
    public function createShipmentTypeStoreQuery(): SpyShipmentTypeStoreQuery
    {
        return SpyShipmentTypeStoreQuery::create();
    }

    /**
     * @return \Spryker\Zed\ShipmentType\Persistence\Propel\Mapper\ShipmentTypeMapper
     */
    public function createShipmentTypeMapper(): ShipmentTypeMapper
    {
        return new ShipmentTypeMapper();
    }
}
