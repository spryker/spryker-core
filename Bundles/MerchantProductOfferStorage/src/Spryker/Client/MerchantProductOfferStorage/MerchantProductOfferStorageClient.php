<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage;

use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
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
     * @param string $productSku
     *
     * @return string[]
     */
    public function getProductOfferReferences(string $productSku): array
    {
        return $this->getFactory()
            ->createProductOfferStorageReader()
            ->getProductOfferReferences($productSku);
    }
}
