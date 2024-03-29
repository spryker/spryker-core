<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePoint\Persistence;

use Orm\Zed\ShipmentTypeServicePoint\Persistence\SpyShipmentTypeServiceTypeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ShipmentTypeServicePoint\Persistence\Propel\Mapper\ShipmentTypeServicePointMapper;

/**
 * @method \Spryker\Zed\ShipmentTypeServicePoint\ShipmentTypeServicePointConfig getConfig()
 * @method \Spryker\Zed\ShipmentTypeServicePoint\Persistence\ShipmentTypeServicePointRepositoryInterface getRepository()
 */
class ShipmentTypeServicePointPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ShipmentTypeServicePoint\Persistence\SpyShipmentTypeServiceTypeQuery
     */
    public function createShipmentTypeServiceTypeQuery(): SpyShipmentTypeServiceTypeQuery
    {
        return SpyShipmentTypeServiceTypeQuery::create();
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeServicePoint\Persistence\Propel\Mapper\ShipmentTypeServicePointMapper
     */
    public function createShipmentTypeServicePointMapper(): ShipmentTypeServicePointMapper
    {
        return new ShipmentTypeServicePointMapper();
    }
}
