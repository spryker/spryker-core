<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Business\StrategyResolver;

use Spryker\Zed\ShipmentCheckoutConnector\Business\Shipment\ShipmentCheckoutPreCheckInterface;

/**
 * @deprecated Will be removed in next major release.
 */
interface PreCheckStrategyResolverInterface
{
    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Spryker\Zed\ShipmentCheckoutConnector\Business\Shipment\ShipmentCheckoutPreCheckInterface
     */
    public function resolve(iterable $itemTransfers): ShipmentCheckoutPreCheckInterface;
}
