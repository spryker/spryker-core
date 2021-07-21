<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOption\Business\Validator\MerchantProductOption;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\MerchantProductOption\Persistence\MerchantProductOptionRepositoryInterface;

class MerchantProductOptionValidator implements MerchantProductOptionValidatorInterface
{
    protected const MESSAGE_PARAM_NAME = '%name%';
    protected const MESSAGE_ERROR_CART_ITEM_OPTION_PRE_CHECK = 'cart.item.option.pre.check.validation.error.exists';
    protected const MESSAGE_ERROR_CHECKOUT_ITEM_OPTION_PRE_CONDITION = 'checkout.item.option.pre.condition.validation.error.exists';

    /**
     * @var \Spryker\Zed\MerchantProductOption\Persistence\MerchantProductOptionRepositoryInterface
     */
    protected $merchantProductOptionRepository;

    /**
     * @param \Spryker\Zed\MerchantProductOption\Persistence\MerchantProductOptionRepositoryInterface $merchantProductOptionRepository
     */
    public function __construct(MerchantProductOptionRepositoryInterface $merchantProductOptionRepository)
    {
        $this->merchantProductOptionRepository = $merchantProductOptionRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateMerchantProductOptionsInCart(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $cartPreCheckResponseTransfer = (new CartPreCheckResponseTransfer())
            ->setIsSuccess(true);
        /** @var int[] $productOptionGroupIds */
        $productOptionGroupIds = $this->extractProductOptionGroupIdsFromItemTransfers($cartChangeTransfer->getItems());

        if (!$productOptionGroupIds) {
            return $cartPreCheckResponseTransfer;
        }

        /** @var int[] $notApprovedProductOptionGroupIds */
        $notApprovedProductOptionGroupIds = $this->merchantProductOptionRepository
            ->getProductOptionGroupIdsWithNotApprovedMerchantGroups($productOptionGroupIds);

        if (!$notApprovedProductOptionGroupIds) {
            return $cartPreCheckResponseTransfer;
        }

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (count($itemTransfer->getProductOptions()) === 0) {
                continue;
            }

            $cartPreCheckResponseTransfer = $this->addErrorsToCartPreCheckResponse(
                $cartPreCheckResponseTransfer,
                $itemTransfer,
                $notApprovedProductOptionGroupIds
            );
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateMerchantProductOptionsOnCheckout(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): CheckoutResponseTransfer {
        $checkoutResponseTransfer = $this->updateCheckoutResponseTransferStatus($checkoutResponseTransfer);
        /** @var int[] $productOptionGroupIds */
        $productOptionGroupIds = $this->extractProductOptionGroupIdsFromItemTransfers($quoteTransfer->getItems());

        if (!$productOptionGroupIds) {
            return $checkoutResponseTransfer;
        }

        /** @var int[] $notApprovedProductOptionGroupIds */
        $notApprovedProductOptionGroupIds = $this->merchantProductOptionRepository
            ->getProductOptionGroupIdsWithNotApprovedMerchantGroups($productOptionGroupIds);

        if (!$notApprovedProductOptionGroupIds) {
            return $checkoutResponseTransfer;
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (count($itemTransfer->getProductOptions()) === 0) {
                continue;
            }

            $checkoutResponseTransfer = $this->addErrorsToCheckoutResponse(
                $checkoutResponseTransfer,
                $itemTransfer,
                $notApprovedProductOptionGroupIds
            );
        }

        return $checkoutResponseTransfer;
    }

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @phpstan-return array<int|null>
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return array
     */
    protected function extractProductOptionGroupIdsFromItemTransfers(ArrayObject $itemTransfers): array
    {
        $productOptionGroupIds = [];

        foreach ($itemTransfers as $itemTransfer) {
            if (count($itemTransfer->getProductOptions()) === 0) {
                continue;
            }

            /** @var int[] $productOptionGroupIds */
            $productOptionGroupIds = $this->extractProductOptionGroupIds($itemTransfer->getProductOptions(), $productOptionGroupIds);
        }

        return $productOptionGroupIds;
    }

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\ProductOptionTransfer> $productOptionTransfers
     *
     * @phpstan-return array<int|null>
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductOptionTransfer[] $productOptionTransfers
     * @param int[] $productOptionGroupIds
     *
     * @return array
     */
    protected function extractProductOptionGroupIds(ArrayObject $productOptionTransfers, array $productOptionGroupIds): array
    {
        /** @var \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer */
        foreach ($productOptionTransfers as $productOptionTransfer) {
            $productOptionGroupIds[] = $productOptionTransfer->getIdGroup();
        }

        return $productOptionGroupIds;
    }

    /**
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int[] $notApprovedProductOptionGroupIds
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function addErrorsToCartPreCheckResponse(
        CartPreCheckResponseTransfer $cartPreCheckResponseTransfer,
        ItemTransfer $itemTransfer,
        array $notApprovedProductOptionGroupIds
    ): CartPreCheckResponseTransfer {
        $cartPreCheckResponseTransfer->setIsSuccess(true);

        if (!$notApprovedProductOptionGroupIds) {
            return $cartPreCheckResponseTransfer;
        }

        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            if (!in_array($productOptionTransfer->getIdGroup(), $notApprovedProductOptionGroupIds)) {
                continue;
            }

            $message = $this->createViolationMessage(static::MESSAGE_ERROR_CART_ITEM_OPTION_PRE_CHECK);
            $message->setParameters([
                static::MESSAGE_PARAM_NAME => $itemTransfer->getName(),
            ]);

            $cartPreCheckResponseTransfer->addMessage($message);
            $cartPreCheckResponseTransfer->setIsSuccess(false);
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int[] $notApprovedProductOptionGroupIds
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function addErrorsToCheckoutResponse(
        CheckoutResponseTransfer $checkoutResponseTransfer,
        ItemTransfer $itemTransfer,
        array $notApprovedProductOptionGroupIds
    ): CheckoutResponseTransfer {
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            if (!in_array($productOptionTransfer->getIdGroup(), $notApprovedProductOptionGroupIds)) {
                continue;
            }

            $checkoutErrorTransfer = new CheckoutErrorTransfer();
            $checkoutErrorTransfer
                ->setMessage(static::MESSAGE_ERROR_CHECKOUT_ITEM_OPTION_PRE_CONDITION)
                ->setParameters([
                    static::MESSAGE_PARAM_NAME => $itemTransfer->getName(),
                ]);

            $checkoutResponseTransfer
                ->addError($checkoutErrorTransfer)
                ->setIsSuccess(false);
        }

        return $checkoutResponseTransfer;
    }

    /**
     * @param string $translationKey
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createViolationMessage(string $translationKey): MessageTransfer
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($translationKey);

        return $messageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function updateCheckoutResponseTransferStatus(CheckoutResponseTransfer $checkoutResponseTransfer): CheckoutResponseTransfer
    {
        if ($checkoutResponseTransfer->getIsSuccess() === false) {
            return $checkoutResponseTransfer;
        }

        $checkoutResponseTransfer->setIsSuccess($checkoutResponseTransfer->getErrors()->count() === 0);

        return $checkoutResponseTransfer;
    }
}
