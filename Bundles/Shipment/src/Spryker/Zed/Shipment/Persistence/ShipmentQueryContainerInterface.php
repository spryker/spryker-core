<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence;

interface ShipmentQueryContainerInterface
{

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery
     */
    public function queryCarriers();

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery
     */
    public function queryActiveCarriers();

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryMethods();

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryActiveMethods();

    /**
     * @param int $idMethod
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryMethodByIdMethod($idMethod);

}
