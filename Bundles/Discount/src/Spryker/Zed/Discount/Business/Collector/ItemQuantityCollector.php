<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface;

class ItemQuantityCollector extends BaseCollector implements CollectorInterface
{
    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface
     */
    protected $comparators;

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface $comparators
     */
    public function __construct(ComparatorOperatorsInterface $comparators)
    {
        $this->comparators = $comparators;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return array<\Generated\Shared\Transfer\DiscountableItemTransfer>
     */
    public function collect(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer)
    {
        $discountableItems = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (
                !$this->comparators->compare($clauseTransfer, $itemTransfer->getQuantity()) &&
                !$this->comparators->compare($clauseTransfer, $this->calculateItemsQuantityByGroupKey($quoteTransfer, $itemTransfer))
            ) {
                continue;
            }

            $discountableItems[] = $this->createDiscountableItemForItemTransfer($itemTransfer);
        }

        return $discountableItems;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $comparedItemTransfer
     *
     * @return int
     */
    protected function calculateItemsQuantityByGroupKey(QuoteTransfer $quoteTransfer, ItemTransfer $comparedItemTransfer): int
    {
        $quantity = 0;
        $groupKey = $comparedItemTransfer->getGroupKey();
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getGroupKey() && $itemTransfer->getGroupKey() === $groupKey) {
                $quantity += $itemTransfer->getQuantity() ?? 0;
            }
        }

        return $quantity;
    }
}
