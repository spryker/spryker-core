<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ShipmentCheckoutConnector\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\ShipmentPersistence;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery;
use SprykerFeature\Zed\Sales\Persistence\SalesQueryContainerInterface;
use SprykerFeature\Zed\Shipment\Persistence\Propel\Base\SpyShipmentCarrierQuery;
use SprykerFeature\Zed\Shipment\Persistence\Propel\SpyShipmentMethodQuery;
use SprykerFeature\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;
use SprykerFeature\Zed\ShipmentCheckoutConnector\ShipmentCheckoutConnectorDependencyProvider;

/**
 * @method ShipmentPersistence getFactory()
 */
class ShipmentCheckoutConnectorQueryContainer extends AbstractQueryContainer implements ShipmentCheckoutConnectorQueryContainerInterface
{

    /**
     * @param $idSalesOrder
     * @return SpySalesOrderQuery
     */
    public function querySalesOrderById($idSalesOrder)
    {
        return $this->getSalesQueryContainer()->querySalesOrderById($idSalesOrder);
    }

    /**
     * @param $idShipmentMethod
     * @return SpyShipmentMethodQuery
     */
    public function queryShipmentOrderById($idShipmentMethod)
    {
        return $this->getSipmentQueryContainer()->queryMethodByIdMethod($idShipmentMethod);
    }

    /**
 * @return SalesQueryContainerInterface
 */
    protected function getSalesQueryContainer()
    {
        return $this->getProvidedDependency(ShipmentCheckoutConnectorDependencyProvider::QUERY_CONTAINER_SALES);
    }

    /**
     * @return ShipmentQueryContainerInterface
     */
    protected function getSipmentQueryContainer()
    {
        return $this->getProvidedDependency(ShipmentCheckoutConnectorDependencyProvider::QUERY_CONTAINER_SHIPMENT);
    }
}
