<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductOfferStorage\Storage;

use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\PriceProductOfferStorage\Dependency\Client\PriceProductOfferStorageToStorageClientInterface;
use Spryker\Client\PriceProductOfferStorage\Dependency\Client\PriceProductOfferStorageToStoreClientInterface;
use Spryker\Client\PriceProductOfferStorage\Dependency\Service\PriceProductOfferStorageToPriceProductServiceInterface;
use Spryker\Client\PriceProductOfferStorage\Dependency\Service\PriceProductOfferStorageToSynchronizationServiceInterface;
use Spryker\Client\PriceProductOfferStorage\Mapper\PriceProductOfferStorageMapperInterface;
use Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface;
use Spryker\Shared\PriceProductOfferStorage\PriceProductOfferStorageConfig;

class PriceProductOfferStorageReader implements PriceProductOfferStorageReaderInterface
{
    /**
     * @var \Spryker\Client\PriceProductOfferStorage\Mapper\PriceProductOfferStorageMapperInterface
     */
    protected $priceProductMapper;

    /**
     * @var \Spryker\Client\PriceProductOfferStorage\Dependency\Service\PriceProductOfferStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\PriceProductOfferStorage\Dependency\Client\PriceProductOfferStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\PriceProductOfferStorage\Dependency\Client\PriceProductOfferStorageToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @var \Spryker\Client\PriceProductOfferStorage\Dependency\Service\PriceProductOfferStorageToPriceProductServiceInterface
     */
    protected $priceProductService;

    /**
     * @var string|null
     */
    protected static $storeName;

    /**
     * @var \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface|null
     */
    protected static $storageKeyBuilder;

    /**
     * @var array<array<\Generated\Shared\Transfer\PriceProductTransfer>>
     */
    protected static $productOfferPricesByIdProductConcrete = [];

    /**
     * @var array<\Spryker\Client\PriceProductOfferStorageExtension\Dependency\Plugin\PriceProductOfferStoragePriceExtractorPluginInterface>
     */
    protected $priceProductOfferStoragePriceExtractorPlugins;

    /**
     * @param \Spryker\Client\PriceProductOfferStorage\Mapper\PriceProductOfferStorageMapperInterface $priceProductMapper
     * @param \Spryker\Client\PriceProductOfferStorage\Dependency\Service\PriceProductOfferStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\PriceProductOfferStorage\Dependency\Client\PriceProductOfferStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\PriceProductOfferStorage\Dependency\Client\PriceProductOfferStorageToStoreClientInterface $storeClient
     * @param \Spryker\Client\PriceProductOfferStorage\Dependency\Service\PriceProductOfferStorageToPriceProductServiceInterface $priceProductService
     * @param array<\Spryker\Client\PriceProductOfferStorageExtension\Dependency\Plugin\PriceProductOfferStoragePriceExtractorPluginInterface> $priceProductOfferStoragePriceExtractorPlugins
     */
    public function __construct(
        PriceProductOfferStorageMapperInterface $priceProductMapper,
        PriceProductOfferStorageToSynchronizationServiceInterface $synchronizationService,
        PriceProductOfferStorageToStorageClientInterface $storageClient,
        PriceProductOfferStorageToStoreClientInterface $storeClient,
        PriceProductOfferStorageToPriceProductServiceInterface $priceProductService,
        array $priceProductOfferStoragePriceExtractorPlugins
    ) {
        $this->priceProductMapper = $priceProductMapper;
        $this->synchronizationService = $synchronizationService;
        $this->storageClient = $storageClient;
        $this->storeClient = $storeClient;
        $this->priceProductService = $priceProductService;
        $this->priceProductOfferStoragePriceExtractorPlugins = $priceProductOfferStoragePriceExtractorPlugins;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function getProductOfferPrices(int $idProductConcrete): array
    {
        if (isset(static::$productOfferPricesByIdProductConcrete[$idProductConcrete])) {
            return static::$productOfferPricesByIdProductConcrete[$idProductConcrete];
        }

        $priceProductOfferKey = $this->generateKey($idProductConcrete);
        $priceProductOfferData = $this->storageClient->get($priceProductOfferKey);

        if (!$priceProductOfferData) {
            static::$productOfferPricesByIdProductConcrete[$idProductConcrete] = [];

            return static::$productOfferPricesByIdProductConcrete[$idProductConcrete];
        }

        unset($priceProductOfferData['_timestamp']);
        $priceProductTransfers = [];

        foreach ($priceProductOfferData as $priceProductOffer) {
            $priceProductTransfer = $this->priceProductMapper->mapPriceProductOfferStorageDataToPriceProductTransfer($priceProductOffer, (new PriceProductTransfer()));
            $groupKey = $this->priceProductService->buildPriceProductGroupKey($priceProductTransfer);
            $priceProductTransfer->setGroupKey($groupKey);

            $priceProductTransfers[] = $priceProductTransfer;
        }

        foreach ($this->priceProductOfferStoragePriceExtractorPlugins as $priceProductOfferStoragePriceExtractorPlugin) {
            $priceProductTransfers = array_merge(
                $priceProductTransfers,
                $priceProductOfferStoragePriceExtractorPlugin->extractProductPrices($priceProductTransfers),
            );
        }

        static::$productOfferPricesByIdProductConcrete[$idProductConcrete] = $priceProductTransfers;

        return $priceProductTransfers;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return string
     */
    protected function generateKey(int $idProductConcrete): string
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setReference((string)$idProductConcrete);
        $synchronizationDataTransfer->setStore($this->getCurrentStoreName());

        return $this->getStorageKeyBuilder()->generateKey($synchronizationDataTransfer);
    }

    /**
     * @return string
     */
    protected function getCurrentStoreName(): string
    {
        if (static::$storeName === null) {
            static::$storeName = $this->storeClient->getCurrentStore()->getName();
        }

        /** @var string $storeName */
        $storeName = static::$storeName;

        return $storeName;
    }

    /**
     * @return \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface
     */
    protected function getStorageKeyBuilder(): SynchronizationKeyGeneratorPluginInterface
    {
        if (static::$storageKeyBuilder === null) {
            static::$storageKeyBuilder = $this->synchronizationService->getStorageKeyBuilder(PriceProductOfferStorageConfig::RESOURCE_PRICE_PRODUCT_OFFER_OFFER_NAME);
        }

        return static::$storageKeyBuilder;
    }
}
