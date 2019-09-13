<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\StrategyResolver;

use Spryker\Zed\Customer\Business\Checkout\CustomerOrderSaverInterface;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
interface OrderSaverStrategyResolverInterface
{
    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Spryker\Zed\Customer\Business\Checkout\CustomerOrderSaverInterface
     */
    public function resolve(iterable $itemTransfers): CustomerOrderSaverInterface;
}
