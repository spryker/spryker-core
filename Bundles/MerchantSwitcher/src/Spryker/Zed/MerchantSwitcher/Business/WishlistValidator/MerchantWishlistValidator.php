<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Business\WishlistValidator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;

class MerchantWishlistValidator implements MerchantWishlistValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validateItems(WishlistTransfer $wishlistTransfer): ValidationResponseTransfer
    {
        $validationResponseTransfer = (new ValidationResponseTransfer())
            ->setIsSuccess(true);

        $wishlistItemTransfers = $wishlistTransfer->getWishlistItems();
        foreach ($wishlistItemTransfers as $itemTransfer) {
            $errorMessageTransfers = $this->validateWishlistItem($itemTransfer, $wishlistTransfer);

            foreach ($errorMessageTransfers as $errorMessageTransfer) {
                $validationResponseTransfer
                    ->setIsSuccess(false)
                    ->addErrorMessage($errorMessageTransfer);
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
