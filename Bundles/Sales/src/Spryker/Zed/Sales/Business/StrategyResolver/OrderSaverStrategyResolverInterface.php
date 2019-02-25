<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\StrategyResolver;

use Spryker\Zed\Sales\Business\Order\SalesOrderSaverInterface;

/**
 * @deprecated Will be removed in next major release.
 */
interface OrderSaverStrategyResolverInterface
{
    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Spryker\Zed\Sales\Business\Order\SalesOrderSaverInterface
     */
    public function resolve(iterable $itemTransfers): SalesOrderSaverInterface;
}
