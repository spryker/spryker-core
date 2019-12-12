<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Communication\Plugin;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartChangeTransferNormalizerPluginInterface;

class ProductOfferCartChangeTransferNormalizerPlugin implements CartChangeTransferNormalizerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Always executable.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return bool
     */
    public function isApplicable(CartChangeTransfer $cartChangeTransfer): bool
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getProductOffer()) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritDoc}
     * - Sets itemTransfer.identifier same as product offer reference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function normalizeCartChangeTransfer(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getProductOffer()) {
                continue;
            }

            $itemTransfer->setItemIdentifier(
                $itemTransfer->getProductOffer()->getProductOfferReference()
            );
        }

        return $cartChangeTransfer;
    }
}
