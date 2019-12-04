<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Communication\Plugin\PriceProduct;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductMatcherStrategyPluginInterface;

class PriceProductOfferPriceProductMatcherStrategyPluginInterface implements PriceProductMatcherStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if item has product offer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isApplicable(PriceProductTransfer $priceProductTransfer, ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getProductOffer() !== null;
    }

    /**
     * {@inheritDoc}
     * - Compares product offer prices.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isProductPrice(PriceProductTransfer $priceProductTransfer, ItemTransfer $itemTransfer): bool
    {
        $itemTransfer
            ->requireProductOffer()
                ->getProductOffer()
                ->requireProductOfferReference();

        return $itemTransfer->getProductOffer()->getProductOfferReference()
            === $priceProductTransfer->getPriceDimension()->getProductOfferReference();
    }
}
