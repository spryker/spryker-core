<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\DiscountPromotionCreator;

use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\DiscountPromotion\Business\Checker\DiscountPromotionItemCheckerInterface;
use Spryker\Zed\DiscountPromotion\Business\Expander\DiscountPromotionQuoteExpanderInterface;
use Spryker\Zed\DiscountPromotion\Business\Model\DiscountCollectorStrategy\PromotionAvailabilityCalculatorInterface;
use Spryker\Zed\DiscountPromotion\Business\Writer\DiscountVoucherQuoteWriterInterface;

class DiscountableItemCreator implements DiscountableItemCreatorInterface
{
    /**
     * @var \Spryker\Zed\DiscountPromotion\Business\Model\DiscountCollectorStrategy\PromotionAvailabilityCalculatorInterface
     */
    protected $promotionAvailabilityCalculator;

    /**
     * @var \Spryker\Zed\DiscountPromotion\Business\Checker\DiscountPromotionItemCheckerInterface
     */
    protected $discountPromotionItemChecker;

    /**
     * @var \Spryker\Zed\DiscountPromotion\Business\Expander\DiscountPromotionQuoteExpanderInterface
     */
    protected $discountPromotionQuoteExpander;

    /**
     * @var \Spryker\Zed\DiscountPromotion\Business\Writer\DiscountVoucherQuoteWriterInterface
     */
    protected $discountVoucherQuoteWriter;

    /**
     * @param \Spryker\Zed\DiscountPromotion\Business\Model\DiscountCollectorStrategy\PromotionAvailabilityCalculatorInterface $promotionAvailabilityCalculator
     * @param \Spryker\Zed\DiscountPromotion\Business\Checker\DiscountPromotionItemCheckerInterface $discountPromotionItemChecker
     * @param \Spryker\Zed\DiscountPromotion\Business\Expander\DiscountPromotionQuoteExpanderInterface $discountPromotionQuoteExpander
     * @param \Spryker\Zed\DiscountPromotion\Business\Writer\DiscountVoucherQuoteWriterInterface $discountVoucherQuoteWriter
     */
    public function __construct(
        PromotionAvailabilityCalculatorInterface $promotionAvailabilityCalculator,
        DiscountPromotionItemCheckerInterface $discountPromotionItemChecker,
        DiscountPromotionQuoteExpanderInterface $discountPromotionQuoteExpander,
        DiscountVoucherQuoteWriterInterface $discountVoucherQuoteWriter
    ) {
        $this->promotionAvailabilityCalculator = $promotionAvailabilityCalculator;
        $this->discountPromotionItemChecker = $discountPromotionItemChecker;
        $this->discountPromotionQuoteExpander = $discountPromotionQuoteExpander;
        $this->discountVoucherQuoteWriter = $discountVoucherQuoteWriter;
    }

    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer|null
     */
    public function createDiscountableItemBySku(
        string $abstractSku,
        QuoteTransfer $quoteTransfer,
        DiscountPromotionTransfer $discountPromotionTransfer,
        DiscountTransfer $discountTransfer
    ): ?DiscountableItemTransfer {
        $promotionItemMaximumQuantity = $this->getPromotionItemMaximumQuantity(
            $discountPromotionTransfer,
            $quoteTransfer,
            $abstractSku,
        );

        if ($promotionItemMaximumQuantity === 0) {
            return null;
        }

        $discountPromotionTransfer->setAbstractSku($abstractSku);
        $discountTransfer->setDiscountPromotion($discountPromotionTransfer);

        $promotionItemInQuote = $this->findPromotionItemInQuote($quoteTransfer, $discountPromotionTransfer);
        if (!$promotionItemInQuote) {
            $this->discountPromotionQuoteExpander->expandWithPromotionItem(
                $discountTransfer,
                $quoteTransfer,
                $discountPromotionTransfer,
                $promotionItemMaximumQuantity,
            );

            $this->discountVoucherQuoteWriter->addDiscountVoucherCode($discountTransfer, $quoteTransfer);

            return null;
        }

        $promotionItemInQuote->setMaxQuantity($promotionItemMaximumQuantity);

        $this->discountVoucherQuoteWriter->removeDiscountVoucherCode($discountTransfer, $quoteTransfer);

        $adjustedQuantity = $this->adjustPromotionItemQuantity($promotionItemInQuote, $promotionItemMaximumQuantity);

        return $this->createPromotionDiscountableItemTransfer($promotionItemInQuote, $adjustedQuantity);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $abstractSku
     *
     * @return int
     */
    protected function getPromotionItemMaximumQuantity(
        DiscountPromotionTransfer $discountPromotionTransfer,
        QuoteTransfer $quoteTransfer,
        string $abstractSku
    ): int {
        $discountPromotionQuantity = $discountPromotionTransfer->getQuantity();
        if (!$discountPromotionQuantity) {
            return 0;
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (
                $this->discountPromotionItemChecker->isItemRelatedToDiscountPromotion(
                    $itemTransfer,
                    $discountPromotionTransfer,
                    $abstractSku,
                )
            ) {
                $discountPromotionQuantity -= $itemTransfer->getQuantity();
            }
        }

        if ($discountPromotionQuantity <= 0) {
            return 0;
        }

        return $this->promotionAvailabilityCalculator->getMaximumQuantityBasedOnAvailability(
            $abstractSku,
            $discountPromotionQuantity,
            $quoteTransfer->getStore(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findPromotionItemInQuote(
        QuoteTransfer $quoteTransfer,
        DiscountPromotionTransfer $discountPromotionTransfer
    ): ?ItemTransfer {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($this->discountPromotionItemChecker->isItemPromotional($discountPromotionTransfer, $itemTransfer)) {
                return $itemTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $promotionItemTransfer
     * @param int $availableMaxQuantity
     *
     * @return int
     */
    protected function adjustPromotionItemQuantity(ItemTransfer $promotionItemTransfer, int $availableMaxQuantity): int
    {
        $currentQuantity = $promotionItemTransfer->getQuantity();
        if ($promotionItemTransfer->getQuantity() > $availableMaxQuantity) {
            $currentQuantity = $promotionItemTransfer->getMaxQuantity();
        }

        return $currentQuantity;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $promotionItemTransfer
     * @param int $currentQuantity
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer
     */
    protected function createPromotionDiscountableItemTransfer(ItemTransfer $promotionItemTransfer, int $currentQuantity): DiscountableItemTransfer
    {
        return (new DiscountableItemTransfer())
            ->setOriginalItem($promotionItemTransfer)
            ->setOriginalItemCalculatedDiscounts($promotionItemTransfer->getCalculatedDiscounts())
            ->setQuantity($currentQuantity)
            ->setUnitPrice($promotionItemTransfer->getUnitPrice())
            ->setUnitGrossPrice($promotionItemTransfer->getUnitGrossPrice());
    }
}
