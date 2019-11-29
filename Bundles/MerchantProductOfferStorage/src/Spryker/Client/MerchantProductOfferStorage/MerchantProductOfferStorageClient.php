<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage;

use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageFactory getFactory()
 */
class MerchantProductOfferStorageClient extends AbstractClient implements MerchantProductOfferStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $productSku
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer
     */
    public function getProductOfferStorageCollection(string $productSku): ProductOfferStorageCollectionTransfer
    {
        return $this->getFactory()
            ->createProductOfferStorageReader()
            ->getProductOfferStorageCollection($productSku);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return string|null
     */
    public function findProductConcreteDefaultProductOffer(ProductViewTransfer $productViewTransfer): ?string
    {
        return $this->getFactory()->createProductConcreteDefaultProductOffer()->findProductOfferReference($productViewTransfer);
    }
}
