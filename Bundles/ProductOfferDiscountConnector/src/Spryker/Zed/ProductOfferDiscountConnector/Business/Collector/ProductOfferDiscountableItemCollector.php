<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferDiscountConnector\Business\Collector;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductOfferDiscountConnector\Business\Checker\ProductOfferDecisionRuleCheckerInterface;

class ProductOfferDiscountableItemCollector implements ProductOfferDiscountableItemCollectorInterface
{
    /**
     * @see \Spryker\Shared\Price\PriceConfig::PRICE_MODE_NET
     *
     * @var string
     */
    protected const PRICE_MODE_NET = 'NET_MODE';

    /**
     * @var \Spryker\Zed\ProductOfferDiscountConnector\Business\Checker\ProductOfferDecisionRuleCheckerInterface
     */
    protected ProductOfferDecisionRuleCheckerInterface $productOfferDecisionRuleChecker;

    /**
     * @param \Spryker\Zed\ProductOfferDiscountConnector\Business\Checker\ProductOfferDecisionRuleCheckerInterface $productOfferDecisionRuleChecker
     */
    public function __construct(ProductOfferDecisionRuleCheckerInterface $productOfferDecisionRuleChecker)
    {
        $this->productOfferDecisionRuleChecker = $productOfferDecisionRuleChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return list<\Generated\Shared\Transfer\DiscountableItemTransfer>
     */
    public function getDiscountableItemsByProductOfferReference(
        QuoteTransfer $quoteTransfer,
        ClauseTransfer $clauseTransfer
    ): array {
        $discountableItemTransfers = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$this->productOfferDecisionRuleChecker->isProductOfferReferenceSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer)) {
                continue;
            }

            $discountableItemTransfers[] = $this->createDiscountableItem($itemTransfer, $quoteTransfer->getPriceModeOrFail());
        }

        return $discountableItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $priceMode
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer
     */
    protected function createDiscountableItem(ItemTransfer $itemTransfer, string $priceMode): DiscountableItemTransfer
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
