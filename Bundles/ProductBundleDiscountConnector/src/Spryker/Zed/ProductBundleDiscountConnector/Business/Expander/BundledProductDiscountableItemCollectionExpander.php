<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleDiscountConnector\Business\Expander;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductBundleDiscountConnector\Business\DecisionRule\ProductBundleAttributeDecisionRuleInterface;

class BundledProductDiscountableItemCollectionExpander implements BundledProductDiscountableItemCollectionExpanderInterface
{
    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_NET
     *
     * @var string
     */
    protected const PRICE_MODE_NET = 'NET_MODE';

    /**
     * @var \Spryker\Zed\ProductBundleDiscountConnector\Business\DecisionRule\ProductBundleAttributeDecisionRuleInterface
     */
    protected $productBundleAttributeDecisionRule;

    /**
     * @param \Spryker\Zed\ProductBundleDiscountConnector\Business\DecisionRule\ProductBundleAttributeDecisionRuleInterface $productBundleAttributeDecisionRule
     */
    public function __construct(ProductBundleAttributeDecisionRuleInterface $productBundleAttributeDecisionRule)
    {
        $this->productBundleAttributeDecisionRule = $productBundleAttributeDecisionRule;
    }

    /**
     * @param array<\Generated\Shared\Transfer\DiscountableItemTransfer> $discountableItems
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return array<\Generated\Shared\Transfer\DiscountableItemTransfer>
     */
    public function expandProductAttributeDiscountableItemCollectionWithBundledProducts(
        array $discountableItems,
        QuoteTransfer $quoteTransfer,
        ClauseTransfer $clauseTransfer
    ): array {
        foreach ($quoteTransfer->getBundleItems() as $bundleItemTransfer) {
            if ($this->productBundleAttributeDecisionRule->isSatisfiedBy($quoteTransfer, $bundleItemTransfer, $clauseTransfer)) {
                $discountableItems = $this->addBundledItemsToDiscountableItems($quoteTransfer, $bundleItemTransfer, $discountableItems);
            }
        }

        return $discountableItems;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $bundleItemTransfer
     * @param array<\Generated\Shared\Transfer\DiscountableItemTransfer> $discountableItems
     *
     * @return array<\Generated\Shared\Transfer\DiscountableItemTransfer>
     */
    protected function addBundledItemsToDiscountableItems(QuoteTransfer $quoteTransfer, ItemTransfer $bundleItemTransfer, array $discountableItems): array
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($bundleItemTransfer->getBundleItemIdentifier() !== $itemTransfer->getRelatedBundleItemIdentifier()) {
                continue;
            }

            $discountableItems[] = $this->createDiscountableItemTransfer($itemTransfer, $quoteTransfer->getPriceModeOrFail());
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
        $price = $this->getPrice($itemTransfer, $priceMode);

        return (new DiscountableItemTransfer())
            ->fromArray($itemTransfer->toArray(), true)
            ->setUnitPrice($price)
            ->setOriginalItemCalculatedDiscounts($itemTransfer->getCalculatedDiscounts())
            ->setOriginalItem($itemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $priceMode
     *
     * @return int
     */
    protected function getPrice(ItemTransfer $itemTransfer, string $priceMode): int
    {
        if ($priceMode === static::PRICE_MODE_NET) {
            return $itemTransfer->getUnitNetPriceOrFail();
        }

        return $itemTransfer->getUnitGrossPriceOrFail();
    }
}
