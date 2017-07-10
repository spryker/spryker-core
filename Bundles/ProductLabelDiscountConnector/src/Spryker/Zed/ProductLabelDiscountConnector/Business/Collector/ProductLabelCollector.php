<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelDiscountConnector\Business\Collector;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductLabelDiscountConnector\Business\DecisionRule\ProductLabelDecisionRuleInterface;

class ProductLabelCollector implements ProductLabelCollectorInterface
{

    /**
     * @var \Spryker\Zed\ProductLabelDiscountConnector\Business\DecisionRule\ProductLabelDecisionRuleInterface
     */
    protected $productLabelDecisionRule;

    /**
     * @param \Spryker\Zed\ProductLabelDiscountConnector\Business\DecisionRule\ProductLabelDecisionRuleInterface $productLabelDecisionRule
     */
    public function __construct(ProductLabelDecisionRuleInterface $productLabelDecisionRule)
    {
        $this->productLabelDecisionRule = $productLabelDecisionRule;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collect(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer)
    {
        $discountableItems = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $isSatisfied = $this->productLabelDecisionRule->isSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);

            if ($isSatisfied) {
                $discountableItems[] = $this->createDiscountableItemTransfer($itemTransfer);
            }
        }

        return $discountableItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer
     */
    protected function createDiscountableItemTransfer(ItemTransfer $itemTransfer)
    {
        $discountableItemTransfer = new DiscountableItemTransfer();
        $discountableItemTransfer->fromArray($itemTransfer->toArray(), true);
        $discountableItemTransfer->setOriginalItemCalculatedDiscounts($itemTransfer->getCalculatedDiscounts());

        return $discountableItemTransfer;
    }

}
