<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ShipmentCheckoutConnector\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;

interface ShipmentCheckoutConnectorQueryContainerInterface
{

    /**
     * @param $idSalesOrder
     *
     * @return SpySalesOrderQuery
     */
    public function querySalesOrderById($idSalesOrder);

    /**
     * @param $idShipmentMethod
     *
     * @return SpyShipmentMethodQuery
     */
    public function queryShipmentOrderById($idShipmentMethod);

}
