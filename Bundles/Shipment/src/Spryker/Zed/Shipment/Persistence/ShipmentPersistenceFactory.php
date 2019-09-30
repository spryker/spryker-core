<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesShipmentQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodPriceQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Shipment\ShipmentConfig getConfig()
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface getRepository()
 */
class ShipmentPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery
     */
    public function createShipmentCarrierQuery()
    {
        return SpyShipmentCarrierQuery::create();
    }

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function createShipmentMethodQuery()
    {
        return SpyShipmentMethodQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipmentQuery
     */
    public function createSalesShipmentQuery()
    {
        return SpySalesShipmentQuery::create();
    }

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPriceQuery
     */
    public function createShipmentMethodPriceQuery()
    {
        return SpyShipmentMethodPriceQuery::create();
    }
}
