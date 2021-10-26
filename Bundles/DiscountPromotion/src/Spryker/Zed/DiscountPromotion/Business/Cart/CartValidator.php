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
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_INVALID_PROMOTIONAL_ITEM = 'cart.promotion.items.invalid_for_quote';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAM_SKU = '%sku%';

    /**
     * @var string
     */
    protected const MESSAGE_TYPE_ERROR = 'error';

    /**
     * @uses \Spryker\Zed\Cart\CartConfig::OPERATION_ADD
     *
     * @var string
     */
    protected const CART_CHANGE_OPERATION_ADD = 'add';

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateCartDiscountPromotions(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $cartPreCheckResponseTransfer = (new CartPreCheckResponseTransfer())->setIsSuccess(true);

        if ($cartChangeTransfer->getOperation() !== static::CART_CHANGE_OPERATION_ADD) {
            return $cartPreCheckResponseTransfer;
        }

        $promotionItemTransfers = $this->getItemsWithDiscountPromotion($cartChangeTransfer);
        $groupedByIdAvailablePromotionItemTransfer = $this->groupAvailableQuotePromotionItemsById($cartChangeTransfer);

        foreach ($promotionItemTransfers as $promotionItemTransfer) {
            if (!empty($groupedByIdAvailablePromotionItemTransfer[$promotionItemTransfer->getIdDiscountPromotion()])) {
                continue;
            }

            $cartPreCheckResponseTransfer->setIsSuccess(false)
                ->addMessage(
                    (new MessageTransfer())
                        ->setType(static::MESSAGE_TYPE_ERROR)
                        ->setValue(static::GLOSSARY_KEY_ERROR_INVALID_PROMOTIONAL_ITEM)
                        ->setParameters([static::GLOSSARY_KEY_PARAM_SKU => $promotionItemTransfer->getSku()]),
                );
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getItemsWithDiscountPromotion(CartChangeTransfer $cartChangeTransfer): array
    {
        $promotionItemTransfers = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getIdDiscountPromotion() !== null) {
                $promotionItemTransfers[] = $itemTransfer;
            }
        }

        return $promotionItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array<\Generated\Shared\Transfer\PromotionItemTransfer[]>
     */
    protected function groupAvailableQuotePromotionItemsById(CartChangeTransfer $cartChangeTransfer): array
    {
        $groupedByIdAvailablePromotionItemTransfers = [];
        foreach ($cartChangeTransfer->getQuote()->getPromotionItems() as $promotionItemTransfer) {
            $groupedByIdAvailablePromotionItemTransfers[$promotionItemTransfer->getIdDiscountPromotion()][] = $promotionItemTransfer;
        }

        return $groupedByIdAvailablePromotionItemTransfers;
    }
}
