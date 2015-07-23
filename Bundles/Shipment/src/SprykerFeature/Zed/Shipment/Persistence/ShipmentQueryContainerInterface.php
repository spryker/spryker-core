<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Persistence;

use SprykerFeature\Zed\Shipment\Persistence\Propel\ShipmentCarrierQuery;
use SprykerFeature\Zed\Shipment\Persistence\Propel\ShipmentMethodQuery;

interface ShipmentQueryContainerInterface
{
    /**
     * @return ShipmentCarrierQuery
     */
    public function queryCarriers();

    /**
     * @return ShipmentMethodQuery
     */
    public function queryMethods();
}
