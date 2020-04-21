<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;

class CartValidator implements CartValidatorInterface
{
    protected const MESSAGE_ERROR_INVALID_PROMOTIONAL_ITEM = 'cart.promotion.items.invalid_for_quote';
    protected const MESSAGE_PARAM_SKU = '%sku%';
    protected const MESSAGE_TYPE_ERROR = 'error';
    protected const CART_CHANGE_OPERATION_ADD = 'add';

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateCartDiscountPromotions(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        if ($cartChangeTransfer->getOperation() !== static::CART_CHANGE_OPERATION_ADD) {
            return (new CartPreCheckResponseTransfer())->setIsSuccess(true);
        }

        $promotionItemTransfers = $this->getItemsWithDiscountPromotion($cartChangeTransfer);
        $groupedByIdAvailablePromotionItemTransfer = $this->groupAvailableQuotePromotionItemsById($cartChangeTransfer);

        foreach ($promotionItemTransfers as $promotionItemTransfer) {
            if (isset($groupedByIdAvailablePromotionItemTransfer[$promotionItemTransfer->getIdDiscountPromotion()])) {
                continue;
            }

            return (new CartPreCheckResponseTransfer())
                ->setIsSuccess(false)
                ->addMessage(
                    (new MessageTransfer())
                        ->setType(static::MESSAGE_TYPE_ERROR)
                        ->setValue(static::MESSAGE_ERROR_INVALID_PROMOTIONAL_ITEM)
                        ->setParameters([static::MESSAGE_PARAM_SKU => $promotionItemTransfer->getSku()])
                );
        }

        return (new CartPreCheckResponseTransfer())->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getItemsWithDiscountPromotion(CartChangeTransfer $cartChangeTransfer): array
    {
        $promotionItemTransfers = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getIdDiscountPromotion()) {
                $promotionItemTransfers[] = $itemTransfer;
            }
        }

        return $promotionItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\PromotionItemTransfer[]
     */
    protected function groupAvailableQuotePromotionItemsById(CartChangeTransfer $cartChangeTransfer): array
    {
        $groupedByIdAvailablePromotionItemTransfers = [];
        foreach ($cartChangeTransfer->getQuote()->getPromotionItems() as $promotionItemTransfer) {
            $groupedByIdAvailablePromotionItemTransfers[$promotionItemTransfer->getIdDiscountPromotion()] = $promotionItemTransfer;
        }

        return $groupedByIdAvailablePromotionItemTransfers;
    }
}
