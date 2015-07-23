<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\ShipmentPersistence;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Shipment\Persistence\Propel\ShipmentCarrierQuery;
use SprykerFeature\Zed\Shipment\Persistence\Propel\ShipmentMethodQuery;

/**
 * @method ShipmentPersistence  getFactory()
 */
class ShipmentQueryContainer extends AbstractQueryContainer implements ShipmentQueryContainerInterface
{
    /**
     * @return ShipmentCarrierQuery
     */
    public function queryCarriers()
    {
        return $this->getFactory()->createPropelShipmentCarrierQuery();
    }

    /**
     * @return ShipmentMethodQuery
     */
    public function queryMethods()
    {
        return $this->getFactory()->createPropelShipmentMethodQuery();
    }
}
