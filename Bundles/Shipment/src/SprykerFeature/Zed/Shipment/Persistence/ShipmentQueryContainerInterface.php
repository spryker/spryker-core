<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Persistence;

use Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;

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
