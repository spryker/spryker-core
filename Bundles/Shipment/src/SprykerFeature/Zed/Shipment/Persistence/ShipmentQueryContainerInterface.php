<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Persistence;

use SprykerFeature\Zed\Shipment\Persistence\Propel\SpyShipmentCarrierQuery;
use SprykerFeature\Zed\Shipment\Persistence\Propel\SpyShipmentMethodQuery;

interface ShipmentQueryContainerInterface
{
    /**
     * @return SpyShipmentCarrierQuery
     */
    public function queryCarriers();

    /**
     * @return SpyShipmentMethodQuery
     */
    public function queryMethods();
}
