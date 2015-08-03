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
     * @return SpyShipmentCarrierQuery
     */
    public function queryActiveCarriers();

    /**
     * @return SpyShipmentMethodQuery
     */
    public function queryMethods();

    /**
     * @return SpyShipmentMethodQuery
     */
    public function queryActiveMethods();

    /**
     * @param int $idMethod
     *
     * @return SpyShipmentMethodQuery
     */
    public function queryMethodByIdMethod($idMethod);
}
