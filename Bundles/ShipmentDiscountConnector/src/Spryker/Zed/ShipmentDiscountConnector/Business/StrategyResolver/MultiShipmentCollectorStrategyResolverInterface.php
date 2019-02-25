<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Business\StrategyResolver;

use Spryker\Zed\ShipmentDiscountConnector\Business\Collector\ShipmentDiscountCollectorInterface;

/**
 * @deprecated Will be removed in next major release.
 */
interface MultiShipmentCollectorStrategyResolverInterface
{
    /**
     * @param string $type
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\Collector\ShipmentDiscountCollectorInterface
     */
    public function resolveByTypeAndItems(string $type, iterable $itemTransfers): ShipmentDiscountCollectorInterface;
}
