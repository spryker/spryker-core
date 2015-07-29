<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\ShipmentPersistence;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Shipment\Persistence\Propel\Base\SpyShipmentCarrierQuery;
use SprykerFeature\Zed\Shipment\Persistence\Propel\SpyShipmentMethodQuery;

/**
 * @method ShipmentPersistence getFactory()
 */
class ShipmentQueryContainer extends AbstractQueryContainer implements ShipmentQueryContainerInterface
{
    /**
     * @return SpyShipmentCarrierQuery
     */
    public function queryCarriers()
    {
        return $this->getFactory()->createPropelSpyShipmentCarrierQuery();
    }

    /**
     * @return SpyShipmentMethodQuery
     */
    public function queryMethods()
    {
        return $this->getFactory()->createPropelSpyShipmentMethodQuery();
    }
}
