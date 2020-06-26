<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Plugin;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\PriceProductOfferStorageExpanderPluginInterface;

/**
 * @method \Spryker\Client\PriceProductStorage\PriceProductStorageClientInterface getClient()
 * @method \Spryker\Client\PriceProductStorage\PriceProductStorageFactory getFactory()
 */
class PriceProductOfferStorageExpanderPlugin extends AbstractPlugin implements PriceProductOfferStorageExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Resolves price for provided product offer storage transfer object.
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
            $this->getClient()->getResolvedCurrentProductPriceTransfer($priceProductFilterTransfer)
        );
    }
}
