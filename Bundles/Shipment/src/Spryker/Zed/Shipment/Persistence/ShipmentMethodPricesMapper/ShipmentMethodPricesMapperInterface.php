<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence\ShipmentMethodPricesMapper;

use ArrayObject;

interface ShipmentMethodPricesMapperInterface
{
    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice[] $shipmentMethodPriceEntities
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[]
     */
    public function mapShipmentMethodPriceEntitiesToMoneyValueTransfers(array $shipmentMethodPriceEntities): ArrayObject;
}
