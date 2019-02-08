<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentGroup;

use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice;

interface ShipmentFetcherInterface
{
    /**
     * @param int $shipmentMethodId
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethod|null
     */
    public function findActiveShipmentMethodWithPricesAndCarrierById(int $shipmentMethodId): ?SpyShipmentMethod;

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $shipmentMethodEntity
     * @param string $currencyIsoCode
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice|null
     */
    public function findMethodPriceByShipmentMethodAndCurrentStoreCurrency(SpyShipmentMethod $shipmentMethodEntity, string $currencyIsoCode): ?SpyShipmentMethodPrice;
}
