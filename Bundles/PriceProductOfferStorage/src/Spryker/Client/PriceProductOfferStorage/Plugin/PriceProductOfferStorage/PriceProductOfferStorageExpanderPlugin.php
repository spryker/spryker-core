<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductOfferStorage\Plugin\PriceProductOfferStorage;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageExpanderPluginInterface;

/**
 * @method \Spryker\Client\PriceProductOfferStorage\PriceProductOfferStorageClientInterface getClient()
 * @method \Spryker\Client\PriceProductOfferStorage\PriceProductOfferStorageFactory getFactory()
 */
class PriceProductOfferStorageExpanderPlugin extends AbstractPlugin implements ProductOfferStorageExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands ProductOfferStorageTransfer with price.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer
     */
    public function expand(ProductOfferStorageTransfer $productOfferStorageTransfer): ProductOfferStorageTransfer
    {
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setIdProductAbstract($productOfferStorageTransfer->getIdProductAbstract())
            ->setIdProduct($productOfferStorageTransfer->getIdProductConcrete())
            ->setProductOfferReference($productOfferStorageTransfer->getProductOfferReference())
            ->setQuantity(1);

        return $productOfferStorageTransfer->setPrice(
            $this->getFactory()
                ->getPriceProductStorageClient()
                ->getResolvedCurrentProductPriceTransfer($priceProductFilterTransfer)
        );
    }
}
