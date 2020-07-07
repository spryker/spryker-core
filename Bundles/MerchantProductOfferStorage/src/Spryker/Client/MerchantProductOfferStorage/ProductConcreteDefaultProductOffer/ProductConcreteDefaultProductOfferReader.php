<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage\ProductConcreteDefaultProductOffer;

use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageReaderInterface;

class ProductConcreteDefaultProductOfferReader implements ProductConcreteDefaultProductOfferReaderInterface
{
    /**
     * @var \Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageReaderInterface
     */
    protected $productOfferStorageReader;

    /**
     * @var \Spryker\Client\MerchantProductOfferStorage\ProductConcreteDefaultProductOffer\ProductConcreteDefaultProductOfferInterface
     */
    protected $defaultProductOffer;

    /**
     * @param \Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageReaderInterface $productOfferStorageReader
     * @param \Spryker\Client\MerchantProductOfferStorage\ProductConcreteDefaultProductOffer\ProductConcreteDefaultProductOfferInterface $defaultProductOffer
     */
    public function __construct(
        ProductOfferStorageReaderInterface $productOfferStorageReader,
        ProductConcreteDefaultProductOfferInterface $defaultProductOffer
    ) {
        $this->productOfferStorageReader = $productOfferStorageReader;
        $this->defaultProductOffer = $defaultProductOffer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
     *
     * @return string|null
     */
    public function findProductOfferReference(ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer): ?string
    {
        if (!$productOfferStorageCriteriaTransfer->getProductConcreteSkus()) {
            return null;
        }

        $productOfferStorageCollectionTransfer = $this->productOfferStorageReader->getProductOffersBySkus($productOfferStorageCriteriaTransfer);

        if (!$productOfferStorageCollectionTransfer->getProductOffersStorage()->count()) {
            return null;
        }

        $defaultProductOffers = $this->defaultProductOffer->getProductOfferReferences(
            $productOfferStorageCollectionTransfer->getProductOffersStorage()->getArrayCopy(),
            $productOfferStorageCriteriaTransfer
        );

        $productConcreteSku = $productOfferStorageCriteriaTransfer->getProductConcreteSkus()[0];
        if (!isset($defaultProductOffers[$productConcreteSku])) {
            return null;
        }

        return $defaultProductOffers[$productConcreteSku];
    }
}
