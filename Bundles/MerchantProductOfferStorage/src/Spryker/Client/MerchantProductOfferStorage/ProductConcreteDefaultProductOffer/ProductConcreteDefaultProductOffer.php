<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage\ProductConcreteDefaultProductOffer;

use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageReaderInterface;
use Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferProviderPluginInterface;

class ProductConcreteDefaultProductOffer implements ProductConcreteDefaultProductOfferInterface
{
    /**
     * @var \Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageReaderInterface
     */
    protected $productOfferStorageReader;

    /**
     * @var \Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferProviderPluginInterface
     */
    protected $defaultProductOfferPlugin;

    /**
     * @param \Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageReaderInterface $productOfferStorageReader
     * @param \Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferProviderPluginInterface $defaultProductOfferPlugin
     */
    public function __construct(ProductOfferStorageReaderInterface $productOfferStorageReader, ProductOfferProviderPluginInterface $defaultProductOfferPlugin)
    {
        $this->productOfferStorageReader = $productOfferStorageReader;
        $this->defaultProductOfferPlugin = $defaultProductOfferPlugin;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
     *
     * @return string|null
     */
    public function findProductOfferReference(ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer): ?string
    {
        $productOfferStorageTransfers = $this->productOfferStorageReader->getProductOfferStorageCollection($productOfferStorageCriteriaTransfer)->getProductOffersStorage()->getArrayCopy();

        if (!$productOfferStorageTransfers) {
            return null;
        }
        $productOfferReferences = array_map(
            function (ProductOfferStorageTransfer $productOfferStorageTransfer) {
                return $productOfferStorageTransfer->getProductOfferReference();
            },
            $productOfferStorageTransfers
        );
        if (
            $productOfferStorageCriteriaTransfer->getProductOfferReference()
            && in_array($productOfferStorageCriteriaTransfer->getProductOfferReference(), $productOfferReferences)
        ) {
            return $productOfferStorageCriteriaTransfer->getProductOfferReference();
        }

        return $this->defaultProductOfferPlugin->provideDefaultProductOfferReference($productOfferReferences);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
     *
     * @return array<string, string>
     */
    public function getProductOfferReferences(ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer): array
    {
        $productOfferStorageCollectionTransfer = $this->productOfferStorageReader->getProductOfferStorageCollection($productOfferStorageCriteriaTransfer);

        if (!$productOfferStorageCollectionTransfer) {
            return [];
        }

        $groupedProductOfferReferences = [];
        foreach ($productOfferStorageCollectionTransfer->getProductOffersStorage() as $productOfferStorageTransfer) {
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

            $defaultProductOffers[$productConcreteSku] = $this->defaultProductOfferPlugin->provideDefaultProductOfferReference($productConcreteProductOffersReferences);
        }

        return $defaultProductOffers;
    }
}
