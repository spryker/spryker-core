<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage\ProductConcreteDefaultProductOffer;

use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferProviderPluginInterface;

class ProductConcreteDefaultProductOffer implements ProductConcreteDefaultProductOfferInterface
{
    /**
     * @var \Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferProviderPluginInterface
     */
    protected $defaultProductOfferPlugin;

    /**
     * @param \Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferProviderPluginInterface $defaultProductOfferPlugin
     */
    public function __construct(ProductOfferProviderPluginInterface $defaultProductOfferPlugin)
    {
        $this->defaultProductOfferPlugin = $defaultProductOfferPlugin;
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

        $groupedProductOfferReferences = [];
        foreach ($productOffersStorageTransfers as $productOfferStorageTransfer) {
            $groupedProductOfferReferences[$productOfferStorageTransfer->getProductConcreteSku()][]
                = $productOfferStorageTransfer->getProductOfferReference();
        }

        $defaultProductOffers = [];
        foreach ($groupedProductOfferReferences as $productConcreteSku => $productConcreteProductOffersReferences) {
            if (
                $productOfferStorageCriteriaTransfer->getProductOfferReference()
                && in_array($productOfferStorageCriteriaTransfer->getProductOfferReference(), $productConcreteProductOffersReferences)
            ) {
                $defaultProductOffers[$productConcreteSku] = $productOfferStorageCriteriaTransfer->getProductOfferReference();

                continue;
            }

            $defaultProductOffers[$productConcreteSku] = $this->defaultProductOfferPlugin
                ->provideDefaultProductOfferReference($productConcreteProductOffersReferences);
        }

        return $defaultProductOffers;
    }
}
