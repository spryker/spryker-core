<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage\Storage;

use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToStorageClientInterface;
use Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToStoreClientInterface;
use Spryker\Client\MerchantProductOfferStorage\Dependency\Service\MerchantProductOfferStorageToSynchronizationServiceInterface;
use Spryker\Client\MerchantProductOfferStorage\Mapper\MerchantProductOfferMapperInterface;
use Spryker\Shared\MerchantProductOfferStorage\MerchantProductOfferStorageConfig;

class ProductOfferStorageReader implements ProductOfferStorageReaderInterface
{
    /**
     * @var \Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\MerchantProductOfferStorage\Dependency\Service\MerchantProductOfferStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\MerchantProductOfferStorage\Mapper\MerchantProductOfferMapperInterface $merchantProductOfferMapper
     */
    protected $merchantProductOfferMapper;

    /**
     * @var \Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\MerchantProductOfferStorage\Dependency\Service\MerchantProductOfferStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\MerchantProductOfferStorage\Mapper\MerchantProductOfferMapperInterface $merchantProductOfferMapper
     * @param \Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToStoreClientInterface $storeClient
     */
    public function __construct(
        MerchantProductOfferStorageToStorageClientInterface $storageClient,
        MerchantProductOfferStorageToSynchronizationServiceInterface $synchronizationService,
        MerchantProductOfferMapperInterface $merchantProductOfferMapper,
        MerchantProductOfferStorageToStoreClientInterface $storeClient
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->merchantProductOfferMapper = $merchantProductOfferMapper;
        $this->storeClient = $storeClient;
    }

    /**
     * @param string $productSku
     *
     * @return string[]
     */
    public function getProductOfferReferences(string $productSku): array
    {
        $concreteProductOffersKey = $this->generateKey($productSku, MerchantProductOfferStorageConfig::RESOURCE_CONCRETE_PRODUCT_PRODUCT_OFFERS_NAME);
        $concreteProductOffers = $this->storageClient->get($concreteProductOffersKey);

        if (!$concreteProductOffers) {
            return [];
        }
        unset($concreteProductOffers['_timestamp']);

        return $concreteProductOffers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer
     */
    public function getProductOfferStorageCollection(ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer): ProductOfferStorageCollectionTransfer
    {
        $productOfferStorageCollection = new ProductOfferStorageCollectionTransfer();

        $concreteProductOffers = $this->getProductOfferReferences($productOfferStorageCriteriaTransfer->getSku());

        if ($concreteProductOffers) {
            foreach ($concreteProductOffers as $key => $concreteProductOffer) {
                if ($key === '_timestamp') {
                    continue;
                }

                $productOfferStorageTransfer = $this->findProductOfferStorageByReference($concreteProductOffer);

                if ($productOfferStorageTransfer === null) {
                    continue;
                }

                if (
                    $productOfferStorageTransfer->getMerchantReference()
                    && $productOfferStorageTransfer->getMerchantReference() !== $productOfferStorageCriteriaTransfer->getMerchantReference()
                    && $productOfferStorageCriteriaTransfer->getMerchantReference() !== null
                ) {
                    continue;
                }

                $productOfferStorageCollection->addProductOfferStorage($productOfferStorageTransfer);
            }
        }

        return $productOfferStorageCollection;
    }

    /**
     * @param string $productOfferReference
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer|null
     */
    public function findProductOfferStorageByReference(string $productOfferReference): ?ProductOfferStorageTransfer
    {
        $merchantProductOfferKey = $this->generateKey($productOfferReference, MerchantProductOfferStorageConfig::RESOURCE_MERCHANT_PRODUCT_OFFER_NAME);
        $concreteProductOfferData = $this->storageClient->get($merchantProductOfferKey);

        if (!$concreteProductOfferData) {
            return null;
        }

        return $this->merchantProductOfferMapper->mapMerchantProductOfferStorageDataToProductOfferStorageTransfer($concreteProductOfferData, (new ProductOfferStorageTransfer()));
    }

    /**
     * @param string $keyName
     * @param string $resourceName
     *
     * @return string
     */
    protected function generateKey(string $keyName, string $resourceName): string
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setReference($keyName);
        $synchronizationDataTransfer->setStore($this->storeClient->getCurrentStore()->getName());

        return $this->synchronizationService
            ->getStorageKeyBuilder($resourceName)
            ->generateKey($synchronizationDataTransfer);
    }
}
