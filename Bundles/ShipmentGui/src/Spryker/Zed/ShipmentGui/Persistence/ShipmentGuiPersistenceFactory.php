<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ShipmentGui\Persistence\ShipmentGuiRepository;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainer;
use Orm\Zed\Sales\Persistence\SpySalesShipmentQuery;

/**
 * @method \Spryker\Zed\ShipmentGui\ShipmentGuiConfig getConfig()
 * @method \Spryker\Zed\ShipmentGui\Persistence\ShipmentGuiQueryContainerInterface getQueryContainer()
 */
class ShipmentGuiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipmentQuery
     */
    public function createSpySalesShipmentQuery(): SpySalesShipmentQuery
    {
        return SpySalesShipmentQuery::create();
    }
}
