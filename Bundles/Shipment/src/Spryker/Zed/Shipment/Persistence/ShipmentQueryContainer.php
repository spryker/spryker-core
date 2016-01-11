<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Shipment\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
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
