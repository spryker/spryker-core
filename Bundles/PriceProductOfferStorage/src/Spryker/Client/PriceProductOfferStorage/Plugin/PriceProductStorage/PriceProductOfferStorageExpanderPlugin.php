<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductOfferStorage\Plugin\PriceProductStorage;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductFilterExpanderPluginInterface;

/**
 * @method \Spryker\Client\PriceProductOfferStorage\PriceProductOfferStorageClientInterface getClient()
 * @method \Spryker\Client\PriceProductOfferStorage\PriceProductOfferStorageFactory getFactory()
 */
class PriceProductOfferStorageExpanderPlugin extends AbstractPlugin implements PriceProductFilterExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands ProductViewTransfer with product offer reference parameter.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    public function expand(ProductViewTransfer $productViewTransfer, PriceProductFilterTransfer $priceProductFilterTransfer): PriceProductFilterTransfer
    {
        if ($productViewTransfer->getProductOfferReference()) {
            $priceProductFilterTransfer->setProductOffer(
                (new ProductOfferTransfer())
                    ->setProductOfferReference($productViewTransfer->getProductOfferReference())
            );
        }

        return $priceProductFilterTransfer;
    }
}
