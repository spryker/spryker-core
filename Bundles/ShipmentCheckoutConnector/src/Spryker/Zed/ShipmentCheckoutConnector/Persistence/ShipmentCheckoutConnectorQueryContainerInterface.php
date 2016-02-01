<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;

interface ShipmentCheckoutConnectorQueryContainerInterface
{

    /**
     * @param $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function querySalesOrderById($idSalesOrder);

    /**
     * @param $idShipmentMethod
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryShipmentOrderById($idShipmentMethod);

}
