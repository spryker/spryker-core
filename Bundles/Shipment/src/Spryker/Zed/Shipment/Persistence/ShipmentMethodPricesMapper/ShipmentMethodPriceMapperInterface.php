<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence\ShipmentMethodPricesMapper;

use ArrayObject;

interface ShipmentMethodPriceMapperInterface
{
    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice[] $shipmentMethodPriceEntities
     * @param \Generated\Shared\Transfer\MoneyValueTransfer[] $moneyValueTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[]
     */
    public function mapShipmentMethodPriceEntitiesToMoneyValueTransfers(
        array $shipmentMethodPriceEntities,
        array $moneyValueTransfers = []
    ): ArrayObject;
}
