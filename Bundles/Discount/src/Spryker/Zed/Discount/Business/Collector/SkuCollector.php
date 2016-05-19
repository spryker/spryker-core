<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;

class SkuCollector implements CollectorInterface
{

    /**
     * @var ComparatorOperators
     */
    protected $comparators;

    /**
     * @param ComparatorOperators $comparators
     */
    public function __construct(ComparatorOperators $comparators)
    {
        $this->comparators = $comparators;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param ClauseTransfer $clauseTransfer
     *
     * @return DiscountableItemTransfer[]
     */
    public function collect(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer)
    {
        $discountableItems = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
             if ($this->comparators->compare($clauseTransfer, $itemTransfer->getSku()) === true) {
                 $discountableItems[] = $this->createDiscountableItemTransfer($itemTransfer);
             }
         }

        return $discountableItems;
    }

    /**
     * @param ItemTransfer $itemTransfer
     *
     * @return DiscountableItemTransfer
     */
    protected function createDiscountableItemTransfer(ItemTransfer $itemTransfer)
    {
        $discountableItemTransfer = new DiscountableItemTransfer();
        $discountableItemTransfer->fromArray($itemTransfer->toArray(), true);
        $discountableItemTransfer->setOriginalItemCalculatedDiscounts($itemTransfer->getCalculatedDiscounts());

        return $discountableItemTransfer;
    }
}
