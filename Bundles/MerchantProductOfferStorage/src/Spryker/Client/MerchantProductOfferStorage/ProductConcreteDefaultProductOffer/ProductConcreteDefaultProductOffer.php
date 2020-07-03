<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage\ProductConcreteDefaultProductOffer;

use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;

class ProductConcreteDefaultProductOffer implements ProductConcreteDefaultProductOfferInterface
{
    /**
     * @var \Spryker\Client\MerchantProductOfferStorage\ProductConcreteDefaultProductOffer\ProductConcreteDefaultProductOfferReaderInterface
     */
    protected $defaultProductOfferReader;

    /**
     * @param \Spryker\Client\MerchantProductOfferStorage\ProductConcreteDefaultProductOffer\ProductConcreteDefaultProductOfferReaderInterface $defaultProductOfferReader
     */
    public function __construct(ProductConcreteDefaultProductOfferReaderInterface $defaultProductOfferReader)
    {
        $this->defaultProductOfferReader = $defaultProductOfferReader;
    }

    /**
     * @phpstan-return array<string, string>
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer[] $productOffersStorageTransfers
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
     *
     * @return string[]
     */
    public function getProductOfferReferences(
        array $productOffersStorageTransfers,
        ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
    ): array {
        if (!$productOfferStorageCriteriaTransfer->getProductConcreteSkus()) {
            return [];
        }

        $defaultProductOffers = [];

        foreach ($productOffersStorageTransfers as $productOfferStorageTransfer) {
            $defaultProductOffers[$productOfferStorageTransfer->getProductConcreteSku()] = $this->defaultProductOfferReader
                ->findProductOfferReference($productOfferStorageCriteriaTransfer);
        }

        return $defaultProductOffers;
    }
}
