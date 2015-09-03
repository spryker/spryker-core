<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ShipmentCheckoutConnector\Persistence;


use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery;
use SprykerFeature\Zed\Shipment\Persistence\Propel\SpyShipmentMethodQuery;

interface ShipmentCheckoutConnectorQueryContainerInterface
{

    /**
     * @param $idSalesOrder
     * @return SpySalesOrderQuery
     */
    public function querySalesOrderById($idSalesOrder);

    /**
     * @param $idShipmentMethod
     * @return SpyShipmentMethodQuery
     */
    public function queryShipmentOrderById($idShipmentMethod);
}
