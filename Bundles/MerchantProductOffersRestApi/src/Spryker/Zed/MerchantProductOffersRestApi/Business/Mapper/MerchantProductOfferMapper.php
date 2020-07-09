<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffersRestApi\Business\Mapper;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;

class MerchantProductOfferMapper implements MerchantProductOfferMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function mapCartItemRequestTransferToPersistentCartChangeTransfer(
        CartItemRequestTransfer $cartItemRequestTransfer,
        PersistentCartChangeTransfer $persistentCartChangeTransfer
    ): PersistentCartChangeTransfer {
        foreach ($persistentCartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku() !== $cartItemRequestTransfer->getSku()) {
                continue;
            }

            $itemTransfer->setProductOfferReference($cartItemRequestTransfer->getProductOfferReference());
            $itemTransfer->setMerchantReference($cartItemRequestTransfer->getMerchantReference());

            break;
        }

        return $persistentCartChangeTransfer;
    }
}
