<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Persistence;

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
