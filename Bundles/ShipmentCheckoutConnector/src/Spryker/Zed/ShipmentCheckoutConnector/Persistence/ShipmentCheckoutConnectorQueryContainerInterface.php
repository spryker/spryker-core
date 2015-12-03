<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ShipmentCheckoutConnectorQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function querySalesOrderById($idSalesOrder);

    /**
     * @param int $idShipmentMethod
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryShipmentOrderById($idShipmentMethod);

}
