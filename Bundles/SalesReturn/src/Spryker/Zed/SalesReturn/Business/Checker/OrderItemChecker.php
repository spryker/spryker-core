<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Checker;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
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
            $this->assertOrderItemRequirements($itemTransfer);

            if (!in_array($itemTransfer->getState()->getName(), $returnableStateNames, true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isOrderItemInReturnableStates(ItemTransfer $itemTransfer): bool
    {
        $this->assertOrderItemRequirements($itemTransfer);

        if (!in_array($itemTransfer->getState()->getName(), $this->salesReturnConfig->getReturnableStateNames(), true)) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function assertOrderItemRequirements(ItemTransfer $itemTransfer): void
    {
        $itemTransfer
            ->requireState()
            ->getState()
                ->requireName();
    }
}
