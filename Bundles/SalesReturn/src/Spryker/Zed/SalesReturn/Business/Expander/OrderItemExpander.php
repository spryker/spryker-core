<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Expander;

use DateTime;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\SalesReturn\SalesReturnConfig;

class OrderItemExpander implements OrderItemExpanderInterface
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
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItemsWithIsReturnable(array $itemTransfers): array
    {
        foreach ($itemTransfers as $itemTransfer) {
            if ($this->isOrderItemOutdated($itemTransfer)) {
                $itemTransfer->setIsReturnable(false);
            }
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isOrderItemOutdated(ItemTransfer $itemTransfer): bool
    {
        if (!$itemTransfer->getCreatedAt()) {
            return true;
        }

        $currentTime = new DateTime('now');
        $createdAt = new DateTime($itemTransfer->getCreatedAt());

        return $currentTime->diff($createdAt)->days >= $this->salesReturnConfig->getReturnableNumberOfDays();
    }
}
