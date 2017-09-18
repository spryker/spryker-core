<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CurrencyDiscountConnector\Business\Model;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CurrencyDiscountConnector\Business\Model\CurrencyDecisionRuleInterface;

class CurrencyCollector implements CurrencyCollectorInterface
{

    /**
     * @var \Spryker\Zed\CurrencyDiscountConnector\Business\Model\CurrencyDecisionRuleInterface
     */
    protected $currencyDecisionRule;

    /**
     * @param \Spryker\Zed\CurrencyDiscountConnector\Business\Model\CurrencyDecisionRuleInterface $currencyDecisionRule
     */
    public function __construct(CurrencyDecisionRuleInterface $currencyDecisionRule)
    {
        $this->currencyDecisionRule = $currencyDecisionRule;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collectDiscountableItemsFor(
        QuoteTransfer $quoteTransfer,
        ClauseTransfer $clauseTransfer
    ) {
        $discountableItems = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$this->currencyDecisionRule->isCurrencySatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer)) {
                continue;
            }
            $discountableItems[] = $itemTransfer;
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
        $discountableItemTransfer->setUnitGrossPrice($this->getPrice($itemTransfer, $priceMode));
        $discountableItemTransfer->setOriginalItemCalculatedDiscounts($itemTransfer->getCalculatedDiscounts());

        return $discountableItemTransfer;
    }

    /**
     * @deprecated This method calculated gross price when in tax mode, because discounts currently working with gross mode, will be removed in the future
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $priceMode
     *
     * @return int
     */
    private function getPrice(ItemTransfer $itemTransfer, $priceMode)
    {
        if ($priceMode === 'NET_MODE') {
            return $itemTransfer->getUnitNetPrice() + (int)round($itemTransfer->getUnitNetPrice() * $itemTransfer->getTaxRate() / 100);
        } else {
            return $itemTransfer->getUnitGrossPrice();
        }
    }

}
