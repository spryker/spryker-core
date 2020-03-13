<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Sanitizer;

use ArrayObject;
use DateTime;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\SalesReturn\SalesReturnConfig;

class OrderItemSanitizer implements OrderItemSanitizerInterface
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
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    public function sanitizeOutdatedOrderItems(ArrayObject $itemTransfers): ArrayObject
    {
        $sanitizedItemTransfers = [];

        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getCreatedAt() && !$this->isOrderItemOutdated($itemTransfer)) {
                $sanitizedItemTransfers[] = $itemTransfer;
            }
        }

        return new ArrayObject($sanitizedItemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isOrderItemOutdated(ItemTransfer $itemTransfer): bool
    {
        $currentTime = new DateTime('now');
        $createdAt = new DateTime($itemTransfer->getCreatedAt());

        $interval = $currentTime->diff($createdAt);

        return $interval->days >= $this->salesReturnConfig->getReturnableNumberOfDays();
    }
}
