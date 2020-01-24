<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DiscountPromotionsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\PromotionItemTransfer;
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
            ->setQuantity($promotionItemTransfer->getMaxQuantity())
            ->setUuid($this->findDiscountPromotionUuid($promotionItemTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\PromotionItemTransfer $promotionItemTransfer
     *
     * @return string
     */
    protected function findDiscountPromotionUuid(PromotionItemTransfer $promotionItemTransfer): string
    {
        $discountTransfer = $promotionItemTransfer->getDiscount();
        if ($discountTransfer === null || $discountTransfer->getDiscountPromotion() === null) {
            return null;
        }

        return $discountTransfer->getDiscountPromotion()->getUuid();
    }
}
