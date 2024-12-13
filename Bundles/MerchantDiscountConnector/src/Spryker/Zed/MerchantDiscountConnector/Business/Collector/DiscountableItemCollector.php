<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantDiscountConnector\Business\Collector;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\MerchantDiscountConnector\Business\Checker\MerchantReferenceDecisionRuleCheckerInterface;

class DiscountableItemCollector implements DiscountableItemCollectorInterface
{
    /**
     * @see \Spryker\Shared\Price\PriceConfig::PRICE_MODE_NET
     *
     * @var string
     */
    protected const PRICE_MODE_NET = 'NET_MODE';

    /**
     * @var \Spryker\Zed\MerchantDiscountConnector\Business\Checker\MerchantReferenceDecisionRuleCheckerInterface
     */
    protected MerchantReferenceDecisionRuleCheckerInterface $merchantReferenceDecisionRuleChecker;

    /**
     * @param \Spryker\Zed\MerchantDiscountConnector\Business\Checker\MerchantReferenceDecisionRuleCheckerInterface $merchantReferenceDecisionRuleChecker
     */
    public function __construct(MerchantReferenceDecisionRuleCheckerInterface $merchantReferenceDecisionRuleChecker)
    {
        $this->merchantReferenceDecisionRuleChecker = $merchantReferenceDecisionRuleChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return list<\Generated\Shared\Transfer\DiscountableItemTransfer>
     */
    public function collectDiscountableItemsByMerchantReference(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer): array
    {
        $discountableItems = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($this->merchantReferenceDecisionRuleChecker->isMerchantReferenceSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer)) {
                $discountableItems[] = $this->createDiscountableItemTransfer($itemTransfer, $quoteTransfer->getPriceModeOrFail());
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
    protected function createDiscountableItemTransfer(ItemTransfer $itemTransfer, string $priceMode): DiscountableItemTransfer
    {
        return (new DiscountableItemTransfer())
            ->fromArray($itemTransfer->toArray(), true)
            ->setUnitPrice($this->getUnitPrice($itemTransfer, $priceMode))
            ->setOriginalItemCalculatedDiscounts($itemTransfer->getCalculatedDiscounts())
            ->setOriginalItem($itemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $priceMode
     *
     * @return int
     */
    protected function getUnitPrice(ItemTransfer $itemTransfer, string $priceMode): int
    {
        if ($priceMode === static::PRICE_MODE_NET) {
            return $itemTransfer->getUnitNetPriceOrFail();
        }

        return $itemTransfer->getUnitGrossPriceOrFail();
    }
}
