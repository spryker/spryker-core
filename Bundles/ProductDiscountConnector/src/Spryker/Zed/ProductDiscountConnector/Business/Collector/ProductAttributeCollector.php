<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscountConnector\Business\Collector;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductDiscountConnector\Business\DecisionRule\ProductAttributeDecisionRuleInterface;

class ProductAttributeCollector implements ProductAttributeCollectorInterface
{

    /**
     * @var \Spryker\Zed\ProductDiscountConnector\Business\DecisionRule\ProductAttributeDecisionRuleInterface
     */
    protected $productAttributeDecisionRule;

    /**
     * @param \Spryker\Zed\ProductDiscountConnector\Business\DecisionRule\ProductAttributeDecisionRuleInterface $productAttributeDecisionRule
     */
    public function __construct(ProductAttributeDecisionRuleInterface $productAttributeDecisionRule)
    {
        $this->productAttributeDecisionRule = $productAttributeDecisionRule;
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
            $isSatisfied = $this->productAttributeDecisionRule
                ->isSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);

            if ($isSatisfied) {
                $discountableItems[] = $this->createDiscountableItemTransfer($itemTransfer, $quoteTransfer->getPriceMode());
            }
        }

        return $discountableItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $priceMode
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer
     */
    protected function createDiscountableItemTransfer(ItemTransfer $itemTransfer, $priceMode)
    {
        $discountableItemTransfer = new DiscountableItemTransfer();
        $discountableItemTransfer->fromArray($itemTransfer->toArray(), true);
        $price = $this->getPrice($itemTransfer, $priceMode);
        $discountableItemTransfer->setUnitPrice($price);
        $discountableItemTransfer->setUnitGrossPrice($price);
        $discountableItemTransfer->setOriginalItemCalculatedDiscounts($itemTransfer->getCalculatedDiscounts());
        $discountableItemTransfer->setOriginalItem($itemTransfer);

        return $discountableItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $priceMode
     *
     * @return int
     */
    protected function getPrice(ItemTransfer $itemTransfer, $priceMode)
    {
        if ($priceMode === 'NET_MODE') {
            return $itemTransfer->getUnitNetPrice();
        } else {
            return $itemTransfer->getUnitGrossPrice();
        }
    }

}
