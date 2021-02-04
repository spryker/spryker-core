<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Business\WishlistValidator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\SingleMerchantWishlistItemsValidationResponseTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;

class MerchantWishlistValidator implements MerchantWishlistValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\SingleMerchantWishlistItemsValidationResponseTransfer
     */
    public function validateItems(WishlistTransfer $wishlistTransfer): SingleMerchantWishlistItemsValidationResponseTransfer
    {
        $validationResponseTransfer = (new SingleMerchantWishlistItemsValidationResponseTransfer())
            ->setIsSuccessful(true);

        $wishlistItemTransfers = $wishlistTransfer->getWishlistItems();
        foreach ($wishlistItemTransfers as $itemTransfer) {
            $errorMessageTransfers = $this->validateWishlistItem($itemTransfer, $wishlistTransfer);

            foreach ($errorMessageTransfers as $errorMessageTransfer) {
                $validationResponseTransfer
                    ->setIsSuccessful(false)
                    ->addError($errorMessageTransfer);
            }
        }

        return $validationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    protected function validateWishlistItem(
        WishlistItemTransfer $wishlistItemTransfer,
        WishlistTransfer $wishlistTransfer
    ): array {
        $errorMessageTransfers = [];

        if ($wishlistItemTransfer->getMerchantReference() !== $wishlistTransfer->getMerchantReference()) {
            $errorMessageTransfers[] = (new MessageTransfer())
                ->setMessage('merchant_switcher.message.product_is_not_available')
                ->setParameters([
                    '%product_name%' => null,
                    '%sku%' => $wishlistItemTransfer->getSku(),
                ]);
        }

        return $errorMessageTransfers;
    }
}
