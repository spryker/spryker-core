<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Model\DiscountCollectorStrategy;

use ArrayObject;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PromotionItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion;
use Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToProductInterface;
use Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface;

class DiscountPromotionCollectorStrategy implements DiscountPromotionCollectorStrategyInterface
{
    /**
     * @var \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface
     */
    protected $discountPromotionQueryContainer;

    /**
     * @var \Spryker\Zed\DiscountPromotion\Business\Model\DiscountCollectorStrategy\PromotionAvailabilityCalculatorInterface
     */
    protected $promotionAvailabilityCalculator;

    /**
     * @param \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToProductInterface $productFacade
     * @param \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface $discountPromotionQueryContainer
     * @param \Spryker\Zed\DiscountPromotion\Business\Model\DiscountCollectorStrategy\PromotionAvailabilityCalculatorInterface $promotionAvailabilityCalculator
     */
    public function __construct(
        DiscountPromotionToProductInterface $productFacade,
        DiscountPromotionQueryContainerInterface $discountPromotionQueryContainer,
        PromotionAvailabilityCalculatorInterface $promotionAvailabilityCalculator
    ) {

        $this->productFacade = $productFacade;
        $this->discountPromotionQueryContainer = $discountPromotionQueryContainer;
        $this->promotionAvailabilityCalculator = $promotionAvailabilityCalculator;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collect(DiscountTransfer $discountTransfer, QuoteTransfer $quoteTransfer)
    {
        $discountPromotionEntity = $this->findDiscountPromotionByIdDiscount($discountTransfer);

        if (!$discountPromotionEntity) {
            return [];
        }

        $idProductAbstract = $this->productFacade->findProductAbstractIdBySku($discountPromotionEntity->getAbstractSku());
        if (!$idProductAbstract) {
            return [];
        }

        $discountPromotionTransfer = $this->hydrateDiscountPromotion($discountPromotionEntity);
        $discountTransfer->setDiscountPromotion($discountPromotionTransfer);

        $promotionMaximumQuantity = $this->promotionAvailabilityCalculator->getMaximumQuantityBasedOnAvailability(
            $idProductAbstract,
            $discountPromotionEntity->getQuantity()
        );

        if ($promotionMaximumQuantity === 0) {
            return [];
        }

        $promotionItemInQuote = $this->findPromotionItem($quoteTransfer, $discountPromotionEntity);

        if (!$promotionItemInQuote) {
            $this->addPromotionItemToQuote(
                $discountTransfer,
                $quoteTransfer,
                $discountPromotionEntity,
                $promotionMaximumQuantity
            );
            $this->storeVoucherCode($discountTransfer, $quoteTransfer);

            return [];
        }

        $promotionItemInQuote->setMaxQuantity($promotionMaximumQuantity);

        $usedNotAppliedCodes = $this->findUsedNotAppliedVoucherCodes($discountTransfer, $quoteTransfer);
        $quoteTransfer->setUsedNotAppliedVoucherCodes($usedNotAppliedCodes);

        $adjustedQuantity = $this->adjustPromotionItemQuantity($promotionItemInQuote, $promotionMaximumQuantity);
        $discountableItemTransfer = $this->createPromotionDiscountableItemTransfer($promotionItemInQuote, $adjustedQuantity);

        return [$discountableItemTransfer];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion $discountPromotionEntity
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findPromotionItem(QuoteTransfer $quoteTransfer, SpyDiscountPromotion $discountPromotionEntity)
    {
        $promotionItemTransfer = null;
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$this->isPromotionItem(
                $discountPromotionEntity->getAbstractSku(),
                $itemTransfer,
                $discountPromotionEntity->getIdDiscountPromotion()
            )) {
                continue;
            }
            return $itemTransfer;
        }
        return null;
    }

    /**
     * @param \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion $discountPromotionEntity
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param int $promotionProductMaximumQuantity
     *
     * @return \Generated\Shared\Transfer\PromotionItemTransfer
     */
    protected function createPromotionItemTransfer(
        SpyDiscountPromotion $discountPromotionEntity,
        DiscountTransfer $discountTransfer,
        $promotionProductMaximumQuantity
    ) {

        $idProductAbstract = $this->productFacade->findProductAbstractIdBySku($discountPromotionEntity->getAbstractSku());

        $promotionItemTransfer = (new PromotionItemTransfer())
            ->setIdDiscountPromotion($discountPromotionEntity->getIdDiscountPromotion())
            ->setAbstractSku($discountPromotionEntity->getAbstractSku())
            ->setIdProductAbstract($idProductAbstract)
            ->setMaxQuantity($promotionProductMaximumQuantity)
            ->setDiscount($discountTransfer);

        return $promotionItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $promotionItemTransfer
     * @param int $availableMaxQuantity
     *
     * @return int
     */
    protected function adjustPromotionItemQuantity(ItemTransfer $promotionItemTransfer, $availableMaxQuantity)
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
    protected function createPromotionDiscountableItemTransfer(ItemTransfer $promotionItemTransfer, $currentQuantity)
    {
        return (new DiscountableItemTransfer())
            ->setOriginalItem($promotionItemTransfer)
            ->setOriginalItemCalculatedDiscounts($promotionItemTransfer->getCalculatedDiscounts())
            ->setQuantity($currentQuantity)
            ->setUnitPrice($promotionItemTransfer->getUnitPrice())
            ->setUnitGrossPrice($promotionItemTransfer->getUnitGrossPrice());
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion|null
     */
    protected function findDiscountPromotionByIdDiscount(DiscountTransfer $discountTransfer)
    {
        return $this->discountPromotionQueryContainer
            ->queryDiscountPromotionByIdDiscount($discountTransfer->getIdDiscount())
            ->findOne();
    }

    /**
     * @param \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion $discountPromotionEntity
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer|null
     */
    protected function hydrateDiscountPromotion(SpyDiscountPromotion $discountPromotionEntity)
    {
        $discountPromotionTransfer = new DiscountPromotionTransfer();
        $discountPromotionTransfer->fromArray($discountPromotionEntity->toArray(), true);

        return $discountPromotionTransfer;
    }

    /**
     * @param string $promotionProductAbstractSku
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $idDiscountPromotion
     *
     * @return bool
     */
    protected function isPromotionItem($promotionProductAbstractSku, ItemTransfer $itemTransfer, $idDiscountPromotion)
    {
        return ($itemTransfer->getAbstractSku() === $promotionProductAbstractSku && $itemTransfer->getIdDiscountPromotion() === $idDiscountPromotion);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idDiscountPromotion
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PromotionItemTransfer[]
     */
    protected function removePromotionFromSuggestions(QuoteTransfer $quoteTransfer, $idDiscountPromotion)
    {
        $updatedPromotionItems = new ArrayObject();
        foreach ($quoteTransfer->getPromotionItems() as $promotionItemTransfer) {
            if ($promotionItemTransfer->getIdDiscountPromotion() === $idDiscountPromotion) {
                continue;
            }
            $updatedPromotionItems->append($promotionItemTransfer);
        }
        return $updatedPromotionItems;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion $discountPromotionEntity
     * @param int $promotionMaximumQuantity
     *
     * @return void
     */
    protected function addPromotionItemToQuote(
        DiscountTransfer $discountTransfer,
        QuoteTransfer $quoteTransfer,
        SpyDiscountPromotion $discountPromotionEntity,
        $promotionMaximumQuantity
    ) {

        $promotionItemTransfer = $this->createPromotionItemTransfer(
            $discountPromotionEntity,
            $discountTransfer,
            $promotionMaximumQuantity
        );

        $quoteTransfer->addPromotionItem($promotionItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function findUsedNotAppliedVoucherCodes(DiscountTransfer $discountTransfer, QuoteTransfer $quoteTransfer)
    {
        $usedNotAppliedCodes = [];
        foreach ($quoteTransfer->getUsedNotAppliedVoucherCodes() as $unusedVoucherCode) {
            if ($unusedVoucherCode === $discountTransfer->getVoucherCode()) {
                continue;
            }
            $usedNotAppliedCodes[] = $unusedVoucherCode;
        }
        return $usedNotAppliedCodes;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function storeVoucherCode(DiscountTransfer $discountTransfer, QuoteTransfer $quoteTransfer)
    {
        if (!$discountTransfer->getVoucherCode()) {
            return;
        }

        $storedUnusedCodes = (array)$quoteTransfer->getUsedNotAppliedVoucherCodes();
        if (!in_array($discountTransfer->getVoucherCode(), $storedUnusedCodes)) {
            $quoteTransfer->addUsedNotAppliedVoucherCode($discountTransfer->getVoucherCode());
        }
    }
}
