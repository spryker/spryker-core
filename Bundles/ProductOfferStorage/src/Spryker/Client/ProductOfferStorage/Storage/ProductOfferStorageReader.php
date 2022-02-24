<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferStorage\Storage;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Spryker\Client\ProductOfferStorage\Dependency\Client\ProductOfferStorageToStorageClientInterface;
use Spryker\Client\ProductOfferStorage\Dependency\Service\ProductOfferStorageToUtilEncodingServiceInterface;
use Spryker\Client\ProductOfferStorage\Mapper\ProductOfferMapperInterface;
use Spryker\Client\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageCollectionSorterPluginInterface;

class ProductOfferStorageReader implements ProductOfferStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductOfferStorage\Dependency\Client\ProductOfferStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductOfferStorage\Dependency\Service\ProductOfferStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\ProductOfferStorage\Mapper\ProductOfferMapperInterface
     */
    protected $productOfferMapper;

    /**
     * @var \Spryker\Client\ProductOfferStorage\Dependency\Client\ProductOfferStorageToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @var \Spryker\Client\ProductOfferStorage\Dependency\Service\ProductOfferStorageToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Client\ProductOfferStorage\Storage\ProductOfferStorageKeyGeneratorInterface
     */
    protected $productOfferStorageKeyGenerator;

    /**
     * @var array<\Spryker\Client\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageExpanderPluginInterface>
     */
    protected $productOfferStorageExpanderPlugins;

    /**
     * @var \Spryker\Client\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageCollectionSorterPluginInterface
     */
    protected $productOfferStorageCollectionSorterPlugin;

    /**
     * @param \Spryker\Client\ProductOfferStorage\Dependency\Client\ProductOfferStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductOfferStorage\Mapper\ProductOfferMapperInterface $productOfferMapper
     * @param \Spryker\Client\ProductOfferStorage\Dependency\Service\ProductOfferStorageToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Client\ProductOfferStorage\Storage\ProductOfferStorageKeyGeneratorInterface $productOfferStorageKeyGenerator
     * @param \Spryker\Client\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageCollectionSorterPluginInterface $productOfferStorageCollectionSorterPlugin
     * @param array<\Spryker\Client\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageExpanderPluginInterface> $productOfferStorageExpanderPlugins
     */
    public function __construct(
        ProductOfferStorageToStorageClientInterface $storageClient,
        ProductOfferMapperInterface $productOfferMapper,
        ProductOfferStorageToUtilEncodingServiceInterface $utilEncodingService,
        ProductOfferStorageKeyGeneratorInterface $productOfferStorageKeyGenerator,
        ProductOfferStorageCollectionSorterPluginInterface $productOfferStorageCollectionSorterPlugin,
        array $productOfferStorageExpanderPlugins
    ) {
        $this->storageClient = $storageClient;
        $this->productOfferMapper = $productOfferMapper;
        $this->utilEncodingService = $utilEncodingService;
        $this->productOfferStorageKeyGenerator = $productOfferStorageKeyGenerator;
        $this->productOfferStorageCollectionSorterPlugin = $productOfferStorageCollectionSorterPlugin;
        $this->productOfferStorageExpanderPlugins = $productOfferStorageExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer
     */
    public function getProductOfferStoragesBySkus(
        ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
    ): ProductOfferStorageCollectionTransfer {
        $productOfferStorageCollectionTransfer = new ProductOfferStorageCollectionTransfer();

        $productConcreteSkus = $productOfferStorageCriteriaTransfer->getProductConcreteSkus();
        if (!$productConcreteSkus) {
            return $productOfferStorageCollectionTransfer;
        }

        $productOfferReferences = $this->getProductOfferReferences($productConcreteSkus);
        if (!$productOfferReferences) {
            return $productOfferStorageCollectionTransfer;
        }

        $productOfferStorageTransfers = $this->getProductOfferStoragesByReferences(array_unique(array_filter($productOfferReferences)));
        if (!$productOfferStorageTransfers) {
            return $productOfferStorageCollectionTransfer;
        }

        $productOfferStorageTransfers = $this->executeProductOfferStorageExpanderPlugins($productOfferStorageTransfers);

        $productOfferStorageCollectionTransfer->setProductOffers(new ArrayObject($productOfferStorageTransfers));
        $productOfferStorageCollectionTransfer = $this->productOfferStorageCollectionSorterPlugin
            ->sort($productOfferStorageCollectionTransfer);

        return $this->expandProductOffersWithDefaultProductOffer($productOfferStorageCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
     *
     * @return string|null
     */
    public function findDefaultProductOfferReference(ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer): ?string
    {
        $productOfferStorageCollectionTransfer = $this->getProductOfferStoragesBySkus(
            (new ProductOfferStorageCriteriaTransfer())
                ->setProductConcreteSkus($productOfferStorageCriteriaTransfer->getProductConcreteSkus()),
        );

        /** @var \ArrayObject $productOfferStorageTransfers */
        $productOfferStorageTransfers = $productOfferStorageCollectionTransfer->getProductOffers();
        $productOfferStorageTransfers = $productOfferStorageTransfers->getArrayCopy();

        if (!$productOfferStorageTransfers) {
            return null;
        }

        return $productOfferStorageTransfers[0] ? $productOfferStorageTransfers[0]->getProductOfferReference() : null;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
     *
     * @return string|null
     */
    public function findProductOfferReference(ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer): ?string
    {
        $productOfferStorageCollectionTransfer = $this->getProductOfferStoragesBySkus(
            (new ProductOfferStorageCriteriaTransfer())
                ->setProductConcreteSkus($productOfferStorageCriteriaTransfer->getProductConcreteSkus()),
        );

        /** @var \ArrayObject $productOfferStorageTransfers */
        $productOfferStorageTransfers = $productOfferStorageCollectionTransfer->getProductOffers();
        $productOfferStorageTransfers = $productOfferStorageTransfers->getArrayCopy();

        if (!$productOfferStorageTransfers) {
            return null;
        }

        $productOfferReferences = array_map(
            function (ProductOfferStorageTransfer $productOfferStorageTransfer) {
                return $productOfferStorageTransfer->getProductOfferReference();
            },
            $productOfferStorageTransfers,
        );

        if (
            $productOfferStorageCriteriaTransfer->getProductOfferReference()
            && in_array($productOfferStorageCriteriaTransfer->getProductOfferReference(), $productOfferReferences, true)
        ) {
            return $productOfferStorageCriteriaTransfer->getProductOfferReference();
        }

        return null;
    }

    /**
     * @param string $productOfferReference
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer|null
     */
    public function findProductOfferStorageByReference(string $productOfferReference): ?ProductOfferStorageTransfer
    {
        $productOfferStorageTransfers = $this->getProductOfferStoragesByReferences([$productOfferReference]);

        return $productOfferStorageTransfers[0] ?? null;
    }

    /**
     * @param array<string> $productOfferReferences
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferStorageTransfer>
     */
    public function getProductOfferStoragesByReferences(array $productOfferReferences): array
    {
        $productOfferKeys = $this->productOfferStorageKeyGenerator->generateProductOfferKeys($productOfferReferences);
        $productOfferData = $this->storageClient->getMulti($productOfferKeys);

        $productOfferStorageTransfers = [];
        foreach ($productOfferData as $productOfferDataItem) {
            if (!$productOfferDataItem) {
                continue;
            }

            $decodedProductOfferStorageData = $this->utilEncodingService->decodeJson($productOfferDataItem, true);

            if (!$decodedProductOfferStorageData) {
                continue;
            }

            $productOfferStorageTransfers[] = $this->productOfferMapper->mapProductOfferStorageDataToProductOfferStorageTransfer(
                $decodedProductOfferStorageData,
                new ProductOfferStorageTransfer(),
            );
        }

        return $productOfferStorageTransfers;
    }

    /**
     * @param array<string> $productConcreteSkus
     *
     * @return array<string>
     */
    protected function getProductOfferReferences(array $productConcreteSkus): array
    {
        $concreteProductOffersKeys = $this->productOfferStorageKeyGenerator
            ->generateProductConcreteProductOffersKeys($productConcreteSkus);
        $concreteProductOffers = $this->storageClient->getMulti($concreteProductOffersKeys);

        $concreteProductOfferReferences = [];
        foreach ($concreteProductOffers as $storageKey => $concreteProductOffer) {
            if (!$concreteProductOffer) {
                continue;
            }

            $decodedConcreteProductOffer = $this->utilEncodingService->decodeJson($concreteProductOffer, true);

            if (!$decodedConcreteProductOffer) {
                continue;
            }

            unset($decodedConcreteProductOffer['_timestamp']);

            $concreteProductOfferReferences = array_merge($concreteProductOfferReferences, $decodedConcreteProductOffer);
        }

        return $concreteProductOfferReferences;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductOfferStorageTransfer> $productOfferStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferStorageTransfer>
     */
    protected function executeProductOfferStorageExpanderPlugins(array $productOfferStorageTransfers): array
    {
        foreach ($productOfferStorageTransfers as $key => $productOfferStorageTransfer) {
            foreach ($this->productOfferStorageExpanderPlugins as $productOfferStorageExpanderPlugin) {
                $productOfferStorageTransfers[$key] = $productOfferStorageExpanderPlugin->expand($productOfferStorageTransfer);
            }
        }

        return $productOfferStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer
     */
    protected function expandProductOffersWithDefaultProductOffer(
        ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer
    ): ProductOfferStorageCollectionTransfer {
        /** @var \ArrayObject $productOfferStorageTransfers */
        $productOfferStorageTransfers = $productOfferStorageCollectionTransfer->getProductOffers();
        if (!$productOfferStorageTransfers->count()) {
            return $productOfferStorageCollectionTransfer;
        }

        $productOffers = $productOfferStorageTransfers->getArrayCopy();

        foreach ($productOffers as $key => $productOffer) {
            $productOffers[$key] = $productOffer->setIsDefault($key < 1);
        }

        return $productOfferStorageCollectionTransfer->setProductOffers(new ArrayObject($productOffers));
    }
}
