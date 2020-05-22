<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage\Storage;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToMerchantStorageClientInterface;
use Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToStorageClientInterface;
use Spryker\Client\MerchantProductOfferStorage\Dependency\Service\MerchantProductOfferStorageToUtilEncodingServiceInterface;
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
     * @var \Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToMerchantStorageClientInterface
     */
    protected $merchantStorageClient;

    /**
     * @var \Spryker\Client\MerchantProductOfferStorage\Dependency\Service\MerchantProductOfferStorageToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageKeyGeneratorInterface
     */
    protected $productOfferStorageKeyGenerator;

    /**
     * @param \Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\MerchantProductOfferStorage\Mapper\MerchantProductOfferMapperInterface $merchantProductOfferMapper
     * @param \Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToMerchantStorageClientInterface $merchantStorageClient
     * @param \Spryker\Client\MerchantProductOfferStorage\Dependency\Service\MerchantProductOfferStorageToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageKeyGeneratorInterface $productOfferStorageKeyGenerator
     */
    public function __construct(
        MerchantProductOfferStorageToStorageClientInterface $storageClient,
        MerchantProductOfferMapperInterface $merchantProductOfferMapper,
        MerchantProductOfferStorageToMerchantStorageClientInterface $merchantStorageClient,
        MerchantProductOfferStorageToUtilEncodingServiceInterface $utilEncodingService,
        ProductOfferStorageKeyGeneratorInterface $productOfferStorageKeyGenerator
    ) {
        $this->storageClient = $storageClient;
        $this->merchantProductOfferMapper = $merchantProductOfferMapper;
        $this->merchantStorageClient = $merchantStorageClient;
        $this->utilEncodingService = $utilEncodingService;
        $this->productOfferStorageKeyGenerator = $productOfferStorageKeyGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer
     */
    public function getProductOfferStorageCollection(
        ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
    ): ProductOfferStorageCollectionTransfer {
        $productOfferStorageCollectionTransfer = new ProductOfferStorageCollectionTransfer();

        $productConcreteSkus = array_merge(
            $productOfferStorageCriteriaTransfer->getProductConcreteSkus(),
            [$productOfferStorageCriteriaTransfer->getSku()]
        );

        $productOfferReferences = $this->getProductOfferReferences(array_unique(array_filter($productConcreteSkus)));

        if (!$productOfferReferences) {
            return $productOfferStorageCollectionTransfer;
        }

        $productOfferStorageTransfers = $this->getProductOfferStorageByReferences($productOfferReferences);

        if (!$productOfferStorageTransfers) {
            return $productOfferStorageCollectionTransfer;
        }

        if ($productOfferStorageCriteriaTransfer->getMerchantReference()) {
            $productOfferStorageTransfers = $this->filterProductOfferStorageTransfersByMerchantReference(
                $productOfferStorageTransfers,
                $productOfferStorageCriteriaTransfer->getMerchantReference()
            );
        }

        $productOfferStorageTransfers = $this->expandProductOffersWithMerchants($productOfferStorageTransfers);

        return $productOfferStorageCollectionTransfer->setProductOffersStorage(new ArrayObject($productOfferStorageTransfers));
    }

    /**
     * @param string $productOfferReference
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer|null
     */
    public function findProductOfferStorageByReference(string $productOfferReference): ?ProductOfferStorageTransfer
    {
        $merchantProductOfferKey = $this->productOfferStorageKeyGenerator->generateKey(
            $productOfferReference,
            MerchantProductOfferStorageConfig::RESOURCE_MERCHANT_PRODUCT_OFFER_NAME
        );
        $concreteProductOfferData = $this->storageClient->get($merchantProductOfferKey);

        if (!$concreteProductOfferData) {
            return null;
        }

        return $this->merchantProductOfferMapper->mapMerchantProductOfferStorageDataToProductOfferStorageTransfer($concreteProductOfferData, (new ProductOfferStorageTransfer()));
    }

    /**
     * @param string[] $productConcreteSkus
     *
     * @return string[]
     */
    protected function getProductOfferReferences(array $productConcreteSkus): array
    {
        $concreteProductOffersKeys = $this->productOfferStorageKeyGenerator->generateProductConcreteProductOffersKeys($productConcreteSkus);
        $concreteProductOffers = $this->storageClient->getMulti($concreteProductOffersKeys);

        $decodedConcreteProductOffers = [];
        foreach ($concreteProductOffers as $storageKey => $concreteProductOffer) {
            if (!$concreteProductOffer) {
                continue;
            }

            $concreteProductOffer = $this->utilEncodingService->decodeJson($concreteProductOffer, true);

            if (!$concreteProductOffer) {
                continue;
            }

            unset($concreteProductOffer['_timestamp']);

            $decodedConcreteProductOffers = array_merge($decodedConcreteProductOffers, $concreteProductOffer);
        }

        return $decodedConcreteProductOffers;
    }

    /**
     * @param string[] $productOfferReferences
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer[]
     */
    public function getProductOfferStorageByReferences(array $productOfferReferences): array
    {
        $merchantProductOfferKeys = $this->productOfferStorageKeyGenerator->generateMerchantProductOfferKeys($productOfferReferences);
        $productOfferData = $this->storageClient->getMulti($merchantProductOfferKeys);

        $productOfferStorageTransfers = [];
        foreach ($productOfferData as $productOfferDataItem) {
            if (!$productOfferDataItem) {
                continue;
            }

            $decodedMerchantProductOfferStorageData = $this->utilEncodingService->decodeJson($productOfferDataItem, true);

            if (!$decodedMerchantProductOfferStorageData) {
                continue;
            }

            $productOfferStorageTransfers[] = $this->merchantProductOfferMapper->mapMerchantProductOfferStorageDataToProductOfferStorageTransfer(
                $decodedMerchantProductOfferStorageData,
                new ProductOfferStorageTransfer()
            );
        }

        return $productOfferStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer[] $productOfferStorageTransfers
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer[]
     */
    protected function filterProductOfferStorageTransfersByMerchantReference(
        array $productOfferStorageTransfers,
        string $merchantReference
    ): array {
        $filteredProductOfferStorageTransfers = [];
        foreach ($productOfferStorageTransfers as $productOfferStorageTransfer) {
            if ($productOfferStorageTransfer->getMerchantReference() !== $merchantReference) {
                continue;
            }

            $filteredProductOfferStorageTransfers[] = $productOfferStorageTransfer;
        }

        return $filteredProductOfferStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer[] $productOfferStorageTransfers
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer[]
     */
    protected function expandProductOffersWithMerchants(array $productOfferStorageTransfers): array
    {
        $merchantIds = $this->getMerchantIds($productOfferStorageTransfers);
        $merchantStorageTransfers = $this->merchantStorageClient->get(array_unique($merchantIds));
        $merchantStorageTransfers = $this->indexMerchantStorageTransfersByIdMerchant($merchantStorageTransfers);

        foreach ($productOfferStorageTransfers as $key => $productOfferStorageTransfer) {
            $merchantStorageTransfer = $merchantStorageTransfers[$productOfferStorageTransfer->getIdMerchant()] ?? null;

            if (!$productOfferStorageTransfer || !$merchantStorageTransfer) {
                unset($productOfferStorageTransfers[$key]);
            }

            $productOfferStorageTransfer->setMerchantStorage($merchantStorageTransfer);
        }

        return $productOfferStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer[] $productOfferStorageTransfers
     *
     * @return int[]
     */
    protected function getMerchantIds(array $productOfferStorageTransfers): array
    {
        $merchantIds = [];
        foreach ($productOfferStorageTransfers as $productOfferStorageTransfer) {
            $merchantIds[] = $productOfferStorageTransfer->getIdMerchant();
        }

        return $merchantIds;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer[] $merchantStorageTransfers
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer[]
     */
    protected function indexMerchantStorageTransfersByIdMerchant(array $merchantStorageTransfers): array
    {
        $indexedMerchantStorageTransfers = [];
        foreach ($merchantStorageTransfers as $merchantStorageTransfer) {
            $indexedMerchantStorageTransfers[$merchantStorageTransfer->getIdMerchant()] = $merchantStorageTransfer;
        }

        return $indexedMerchantStorageTransfers;
    }
}
