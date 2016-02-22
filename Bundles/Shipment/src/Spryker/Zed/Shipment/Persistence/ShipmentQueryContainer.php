<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence;

use Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;

/**
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentPersistenceFactory getFactory()
 */
class ShipmentQueryContainer extends AbstractQueryContainer implements ShipmentQueryContainerInterface
{

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery
     */
    public function queryCarriers()
    {
        return $this->getFactory()->createShipmentCarrierQuery();
    }

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery
     */
    public function queryActiveCarriers()
    {
        return $this->queryCarriers()->filterByIsActive(true);
    }

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryMethods()
    {
        return $this->getFactory()->createShipmentMethodQuery();
    }

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryActiveMethods()
    {
        return $this->queryMethods()->filterByIsActive(true);
    }

    /**
     * @param int $idMethod
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryMethodByIdMethod($idMethod)
    {
        $query = $this->queryMethods();
        $query->filterByIdShipmentMethod($idMethod);

        return $query;
    }

}
