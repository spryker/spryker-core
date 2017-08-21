<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Model\DiscountCollectorStrategy;

use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion;
use Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToAvailabilityInterface;
use Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToLocaleInterface;
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
     * @var \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToAvailabilityInterface
     */
    protected $availabilityFacade;

    /**
     * @var \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToProductInterface $productFacade
     * @param \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface $discountPromotionQueryContainer
     * @param \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToAvailabilityInterface $availabilityFacade
     * @param \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToLocaleInterface $localeFacade
     */
    public function __construct(
        DiscountPromotionToProductInterface $productFacade,
        DiscountPromotionQueryContainerInterface $discountPromotionQueryContainer,
        DiscountPromotionToAvailabilityInterface $availabilityFacade,
        DiscountPromotionToLocaleInterface $localeFacade
    ) {

        $this->productFacade = $productFacade;
        $this->discountPromotionQueryContainer = $discountPromotionQueryContainer;
        $this->availabilityFacade = $availabilityFacade;
        $this->localeFacade = $localeFacade;
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

        $discountPromotionTransfer = $this->hydrateDiscountPromotion($discountPromotionEntity);
        $discountTransfer->setDiscountPromotion($discountPromotionTransfer);

        $promotionProductAbstractSku = $discountPromotionEntity->getAbstractSku();
        $promotionProductMaximumQuantity = $discountPromotionEntity->getQuantity();

        if (!$this->isProductAbstractAvailable($promotionProductAbstractSku, $promotionProductMaximumQuantity)) {
            return [];
        }

        $promotionItemInQuote = $this->findPromotionItem(
            $quoteTransfer,
            $promotionProductAbstractSku,
            $promotionProductMaximumQuantity
        );

        if (!$promotionItemInQuote) {
            $this->addPromotionItemToQuote(
                $quoteTransfer,
                $promotionProductAbstractSku,
                $promotionProductMaximumQuantity
            );
            return [];
        }

        $adjustedQuantity = $this->adjustPromotionalItemQuantity($promotionItemInQuote);
        $discountableItemTransfer = $this->createPromotionDiscountableItemTransfer(
            $promotionItemInQuote,
            $adjustedQuantity
        );

        return [$discountableItemTransfer];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $promotionProductAbstractSku
     * @param int $promotionMaximumQuantity
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findPromotionItem(
        QuoteTransfer $quoteTransfer,
        $promotionProductAbstractSku,
        $promotionMaximumQuantity
    ) {

        $promotionItemTransfer = null;
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$this->isPromotionalItem($promotionProductAbstractSku, $itemTransfer)) {
                continue;
            }
            $itemTransfer->setMaxQuantity($promotionMaximumQuantity);
            return $itemTransfer;
        }
        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $promotionProductAbstractSku
     * @param string $promotionProductAbstractQuantity
     *
     * @return void
     */
    protected function addPromotionItemToQuote(
        QuoteTransfer $quoteTransfer,
        $promotionProductAbstractSku,
        $promotionProductAbstractQuantity
    ) {

        $idProductAbstract = $this->productFacade->findProductAbstractIdBySku($promotionProductAbstractSku);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setAbstractSku($promotionProductAbstractSku);
        $itemTransfer->setIdProductAbstract($idProductAbstract);
        $itemTransfer->setMaxQuantity($promotionProductAbstractQuantity);

        $quoteTransfer->addPromotionItem($itemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $promotionItemTransfer
     *
     * @return int
     */
    protected function adjustPromotionalItemQuantity(ItemTransfer $promotionItemTransfer)
    {
        $currentQuantity = $promotionItemTransfer->getQuantity();
        if ($promotionItemTransfer->getQuantity() > $promotionItemTransfer->getMaxQuantity()) {
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
        $discountableItemTransfer = new DiscountableItemTransfer();
        $discountableItemTransfer->setOriginalItem($promotionItemTransfer);
        $discountableItemTransfer->setOriginalItemCalculatedDiscounts($promotionItemTransfer->getCalculatedDiscounts());
        $discountableItemTransfer->setQuantity($currentQuantity);
        $discountableItemTransfer->setUnitGrossPrice($promotionItemTransfer->getUnitGrossPrice());

        return $discountableItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion
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
     *
     * @return bool
     */
    protected function isPromotionalItem($promotionProductAbstractSku, ItemTransfer $itemTransfer)
    {
        return ($itemTransfer->getAbstractSku() === $promotionProductAbstractSku && $itemTransfer->getIsPromotion() === true);
    }

    /**
     * @param $promotionProductAbstractSku
     * @param int $quantity
     *
     * @return bool
     */
    protected function isProductAbstractAvailable($promotionProductAbstractSku, $quantity)
    {
        $localeTransfer = $this->localeFacade->getCurrentLocale();

        $productAbstractAvailabilityTransfer = $this->availabilityFacade
            ->getProductAbstractAvailability(
                $promotionProductAbstractSku,
                $localeTransfer->getIdLocale()
            );

        if ($productAbstractAvailabilityTransfer->getIsNeverOutOfStock()) {
            return true;
        }

        if ($quantity > $productAbstractAvailabilityTransfer->getAvailability()) {
            return false;
        }

        return true;
    }

}
