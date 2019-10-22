<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage\Storage;

use Generated\Shared\Transfer\ProductOfferViewCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferViewTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToStorageClientInterface;
use Spryker\Client\MerchantProductOfferStorage\Dependency\Service\MerchantProductOfferStorageToSynchronizationServiceInterface;
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
     * @param \Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\MerchantProductOfferStorage\Dependency\Service\MerchantProductOfferStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        MerchantProductOfferStorageToStorageClientInterface $storageClient,
        MerchantProductOfferStorageToSynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductOfferViewCollectionTransfer
     */
    public function findProductOffersByConcreteSku(string $concreteSku): ProductOfferViewCollectionTransfer
    {
        $productOfferViewCollectionTransfer = new ProductOfferViewCollectionTransfer();
        $concreteProductOffersKey = $this->generateKey($concreteSku, MerchantProductOfferStorageConfig::RESOURCE_CONCRETE_PRODUCT_PRODUCT_OFFERS_NAME);

        $concreteProductOffers = $this->storageClient->get($concreteProductOffersKey);

        foreach ($concreteProductOffers as $concreteProductOffer) {
            $merchantProductOfferKey = $this->generateKey($concreteProductOffer, MerchantProductOfferStorageConfig::RESOURCE_MERCHANT_PRODUCT_OFFER_NAME);
            $concreteProductOffer = $this->storageClient->get($merchantProductOfferKey);
            $productOfferViewCollectionTransfer[] = $this->mapConcreteProductOffer($concreteProductOffer, (new ProductOfferViewTransfer()));
        }
        
        return $productOfferViewCollectionTransfer;
    }

    /**
     * @param array $concreteProductOffer
     * @param \Generated\Shared\Transfer\ProductOfferViewTransfer $productOfferViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferViewTransfer
     */
    protected function mapConcreteProductOffer(array $concreteProductOffer, ProductOfferViewTransfer $productOfferViewTransfer): ProductOfferViewTransfer
    {
        return $productOfferViewTransfer->fromArray($concreteProductOffer);
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

        return $this->synchronizationService
            ->getStorageKeyBuilder($resourceName)
            ->generateKey($synchronizationDataTransfer);
    }
}
