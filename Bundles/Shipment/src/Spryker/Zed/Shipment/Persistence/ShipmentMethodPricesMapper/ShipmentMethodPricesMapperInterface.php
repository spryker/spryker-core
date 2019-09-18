<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence\ShipmentMethodPricesMapper;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;

interface ShipmentMethodPricesMapperInterface
{
    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice[] $shipmentMethodPriceEntities
     * @param MoneyValueTransfer[] $moneyValueTransfers
     * @return \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[]
     */
    public function mapShipmentMethodPriceEntitiesToMoneyValueTransfers(
        array $shipmentMethodPriceEntities,
        array $moneyValueTransfers = []
    ): ArrayObject;
}
