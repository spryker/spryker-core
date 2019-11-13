<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductOfferStorage\Storage;

use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\PriceProductOfferStorage\Dependency\Client\PriceProductOfferStorageToStorageClientInterface;
use Spryker\Client\PriceProductOfferStorage\Dependency\Client\PriceProductOfferStorageToStoreClientInterface;
use Spryker\Client\PriceProductOfferStorage\Dependency\Service\PriceProductOfferStorageToSynchronizationServiceInterface;
use Spryker\Client\PriceProductOfferStorage\Mapper\PriceProductOfferStorageMapperInterface;
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
     * @param \Spryker\Client\PriceProductOfferStorage\Mapper\PriceProductOfferStorageMapperInterface $priceProductMapper
     * @param \Spryker\Client\PriceProductOfferStorage\Dependency\Service\PriceProductOfferStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\PriceProductOfferStorage\Dependency\Client\PriceProductOfferStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\PriceProductOfferStorage\Dependency\Client\PriceProductOfferStorageToStoreClientInterface $storeClient
     */
    public function __construct(
        PriceProductOfferStorageMapperInterface $priceProductMapper,
        PriceProductOfferStorageToSynchronizationServiceInterface $synchronizationService,
        PriceProductOfferStorageToStorageClientInterface $storageClient,
        PriceProductOfferStorageToStoreClientInterface $storeClient
    ) {
        $this->priceProductMapper = $priceProductMapper;
        $this->synchronizationService = $synchronizationService;
        $this->storageClient = $storageClient;
        $this->storeClient = $storeClient;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findPriceProductList(int $idProductConcrete): array
    {
        $priceProductOfferKey = $this->generateKey($idProductConcrete);
        $priceProductOfferData = $this->storageClient->get($priceProductOfferKey);
        if (empty($priceProductOfferData)) {
            return [];
        }
        unset($priceProductOfferData['_timestamp']);
        $priceProductList = [];

        foreach ($priceProductOfferData as $priceProductOffer) {
            $priceProductList[] = $this->priceProductMapper->mapPriceProductOfferStorageDataToPriceProductTransfer($priceProductOffer, (new PriceProductTransfer()));
        }

        return $priceProductList;
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
        $synchronizationDataTransfer->setStore($this->storeClient->getCurrentStore()->getName());

        return $this->synchronizationService
            ->getStorageKeyBuilder(PriceProductOfferStorageConfig::RESOURCE_PRICE_PRODUCT_OFFER_OFFER_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
