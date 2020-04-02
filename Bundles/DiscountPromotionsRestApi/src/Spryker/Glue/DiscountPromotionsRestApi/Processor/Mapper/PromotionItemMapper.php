<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DiscountPromotionsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\PromotionItemTransfer;
use Generated\Shared\Transfer\RestDiscountsAttributesTransfer;
use Generated\Shared\Transfer\RestPromotionalItemsAttributesTransfer;

class PromotionItemMapper implements PromotionItemMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PromotionItemTransfer $promotionItemTransfer
     * @param \Generated\Shared\Transfer\RestPromotionalItemsAttributesTransfer $restPromotionalItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestPromotionalItemsAttributesTransfer
     */
    public function mapPromotionItemTransferToRestPromotionalItemsAttributesTransfer(
        PromotionItemTransfer $promotionItemTransfer,
        RestPromotionalItemsAttributesTransfer $restPromotionalItemsAttributesTransfer
    ): RestPromotionalItemsAttributesTransfer {
        return $restPromotionalItemsAttributesTransfer
            ->fromArray($promotionItemTransfer->toArray(), true)
            ->setSku($promotionItemTransfer->getAbstractSku())
            ->setQuantity($promotionItemTransfer->getMaxQuantity());
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\RestDiscountsAttributesTransfer $restDiscountsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestDiscountsAttributesTransfer
     */
    public function mapDiscountPromotionToRestDiscountsAttributesTransfer(
        DiscountTransfer $discountTransfer,
        RestDiscountsAttributesTransfer $restDiscountsAttributesTransfer
    ): RestDiscountsAttributesTransfer {
        $discountPromotionTransfer = $discountTransfer->getDiscountPromotion();

        if (!$discountPromotionTransfer) {
            return $restDiscountsAttributesTransfer;
        }

        return $restDiscountsAttributesTransfer
            ->setDiscountPromotionAbstractSku($discountPromotionTransfer->getAbstractSku())
            ->setDiscountPromotionQuantity($discountPromotionTransfer->getQuantity());
    }
}
