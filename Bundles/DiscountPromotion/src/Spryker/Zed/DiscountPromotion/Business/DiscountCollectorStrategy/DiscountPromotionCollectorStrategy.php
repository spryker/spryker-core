<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\DiscountCollectorStrategy;

use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
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
     * @param \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToProductInterface $productFacade
     * @param \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface $discountPromotionQueryContainer
     */
    public function __construct(
        DiscountPromotionToProductInterface $productFacade,
        DiscountPromotionQueryContainerInterface $discountPromotionQueryContainer
    ) {

        $this->productFacade = $productFacade;
        $this->discountPromotionQueryContainer = $discountPromotionQueryContainer;
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
        $promotionProductAbstractQuantity = $discountPromotionEntity->getQuantity();

        $promotionItemTransferInQuote = $this->findPromotionItem(
            $quoteTransfer,
            $promotionProductAbstractSku,
            $promotionProductAbstractQuantity
        );

        if (!$promotionItemTransferInQuote) {
            $this->addPromotionItemToQuote(
                $quoteTransfer,
                $promotionProductAbstractSku,
                $promotionProductAbstractQuantity
            );
            return [];
        }

        $currentQuantity = $this->adjustPromotionalItemQuantity($promotionItemTransferInQuote);
        $discountableItemTransfer = $this->createPromotionDiscountableItemTransfer(
            $promotionItemTransferInQuote,
            $currentQuantity
        );

        return [$discountableItemTransfer];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $promotionProductAbstractSku
     * @param int $promotionProductAbstractQuantity
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findPromotionItem(
        QuoteTransfer $quoteTransfer,
        $promotionProductAbstractSku,
        $promotionProductAbstractQuantity
    ) {

        $promotionItemTransfer = null;
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getAbstractSku() == $promotionProductAbstractSku) { //@todo filter by promotion flag
                $promotionItemTransfer = $itemTransfer;
                $promotionItemTransfer->setMaxQuantity($promotionProductAbstractQuantity);
                return $promotionItemTransfer;
            }
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

}
