<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Checker;

use ArrayObject;
use Spryker\Zed\SalesReturn\SalesReturnConfig;

class OrderItemChecker implements OrderItemCheckerInterface
{
    /**
     * @var \Spryker\Zed\SalesReturn\SalesReturnConfig
     */
    protected $salesReturnConfig;

    /**
     * @param \Spryker\Zed\SalesReturn\SalesReturnConfig $salesReturnConfig
     */
    public function __construct(SalesReturnConfig $salesReturnConfig)
    {
        $this->salesReturnConfig = $salesReturnConfig;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return bool
     */
    public function isOrderItemsInReturnableStates(ArrayObject $itemTransfers): bool
    {
        $returnableStateNames = $this->salesReturnConfig->getReturnableStateNames();

        foreach ($itemTransfers as $itemTransfer) {
            $itemTransfer
                ->requireState()
                ->getState()
                    ->requireName();

            if (!in_array($itemTransfer->getState()->getName(), $returnableStateNames, true)) {
                return false;
            }
        }

        return true;
    }
}
