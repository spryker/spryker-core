<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDiscountConnector\Business\Reader;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CategoryDiscountConnector\Business\Checker\CategoryDecisionRuleCheckerInterface;

class DiscountableItemReader implements DiscountableItemReaderInterface
{
    /**
     * @see \Spryker\Shared\Price\PriceConfig::PRICE_MODE_NET
     *
     * @var string
     */
    protected const PRICE_MODE_NET = 'NET_MODE';

    /**
     * @var \Spryker\Zed\CategoryDiscountConnector\Business\Checker\CategoryDecisionRuleCheckerInterface
     */
    protected $categoryDecisionRuleChecker;

    /**
     * @param \Spryker\Zed\CategoryDiscountConnector\Business\Checker\CategoryDecisionRuleCheckerInterface $categoryDecisionRuleChecker
     */
    public function __construct(CategoryDecisionRuleCheckerInterface $categoryDecisionRuleChecker)
    {
        $this->categoryDecisionRuleChecker = $categoryDecisionRuleChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return array<\Generated\Shared\Transfer\DiscountableItemTransfer>
     */
    public function getDiscountableItemsByCategory(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer): array
    {
        $discountableItemTransfers = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$this->categoryDecisionRuleChecker->isCategorySatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer)) {
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
