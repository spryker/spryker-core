<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantStorage\Storage;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\MerchantStorage\Dependency\Client\MerchantStorageToStorageClientInterface;
use Spryker\Client\MerchantStorage\Dependency\Client\MerchantStorageToStoreClientInterface;
use Spryker\Client\MerchantStorage\Dependency\Service\MerchantStorageToSynchronizationServiceInterface;
use Spryker\Client\MerchantStorage\Dependency\Service\MerchantStorageToUtilEncodingServiceInterface;
use Spryker\Client\MerchantStorage\Mapper\MerchantStorageMapperInterface;
use Spryker\Shared\MerchantStorage\MerchantStorageConfig;

class MerchantStorageReader implements MerchantStorageReaderInterface
{
    protected const KEY_ID_MERCHANT = 'id';
    protected const KEY_MERCHANT_REFERENCE = 'merchant_reference';

    /**
     * @var \Spryker\Client\MerchantStorage\Mapper\MerchantStorageMapperInterface
     */
    protected $merchantStorageMapper;

    /**
     * @var \Spryker\Client\MerchantStorage\Dependency\Service\MerchantStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\MerchantStorage\Dependency\Client\MerchantStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\MerchantStorage\Dependency\Service\MerchantStorageToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Client\MerchantStorage\Dependency\Client\MerchantStorageToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \Spryker\Client\MerchantStorage\Mapper\MerchantStorageMapperInterface $merchantStorageMapper
     * @param \Spryker\Client\MerchantStorage\Dependency\Service\MerchantStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\MerchantStorage\Dependency\Client\MerchantStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\MerchantStorage\Dependency\Service\MerchantStorageToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Client\MerchantStorage\Dependency\Client\MerchantStorageToStoreClientInterface $storeClient
     */
    public function __construct(
        MerchantStorageMapperInterface $merchantStorageMapper,
        MerchantStorageToSynchronizationServiceInterface $synchronizationService,
        MerchantStorageToStorageClientInterface $storageClient,
        MerchantStorageToUtilEncodingServiceInterface $utilEncodingService,
        MerchantStorageToStoreClientInterface $storeClient
    ) {
        $this->merchantStorageMapper = $merchantStorageMapper;
        $this->synchronizationService = $synchronizationService;
        $this->storageClient = $storageClient;
        $this->utilEncodingService = $utilEncodingService;
        $this->storeClient = $storeClient;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer|null
     */
    public function findOne(MerchantCriteriaTransfer $merchantCriteriaTransfer): ?MerchantStorageTransfer
    {
        $merchantKey = $this->generateKey((string)$merchantCriteriaTransfer->getIdMerchant());
        $merchantData = $this->storageClient->get($merchantKey);
        if (!$merchantData) {
            return null;
        }

        return $this->merchantStorageMapper->mapMerchantStorageDataToMerchantStorageTransfer($merchantData);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer[]
     */
    public function get(MerchantCriteriaTransfer $merchantCriteriaTransfer): array
    {
        $merchantStorageTransfers = [];

        $merchantKeys = array_map(function ($idMerchant) {
            return $this->generateKey((string)$idMerchant);
        }, $merchantCriteriaTransfer->getMerchantIds());

        $merchantDataList = $this->storageClient->getMulti($merchantKeys);

        foreach ($merchantDataList as $merchantData) {
            if ($merchantData === null) {
                continue;
            }
            $merchantStorageTransfers[] = $this->merchantStorageMapper->mapMerchantStorageDataToMerchantStorageTransfer(
                $this->utilEncodingService->decodeJson($merchantData, true)
            );
        }

        return $merchantStorageTransfers;
    }

    /**
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer|null
     */
    public function findOneByMerchantReference(string $merchantReference): ?MerchantStorageTransfer
    {
        $merchantKey = $this->generateKey(static::KEY_MERCHANT_REFERENCE . ':' . $merchantReference);
        $merchantDataMapId = $this->storageClient->get($merchantKey);

        if (!isset($merchantDataMapId[static::KEY_ID_MERCHANT])) {
            return null;
        }

        $merchantCriteriaTransfer = new MerchantCriteriaTransfer();

        $merchantCriteriaTransfer->setIdMerchant($merchantDataMapId[static::KEY_ID_MERCHANT]);

        return $this->findOne($merchantCriteriaTransfer);
    }

    /**
     * @param string[] $merchantReferences
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer[]
     */
    public function getByMerchantReferences(array $merchantReferences): array
    {
        $merchantMapKeys = array_map(function ($merchantReference) {
            return $this->generateKey(static::KEY_MERCHANT_REFERENCE . ':' . $merchantReference);
        }, $merchantReferences);

        $merchantDataMapIdList = $this->storageClient->getMulti($merchantMapKeys);

        $merchantIds = [];

        foreach ($merchantDataMapIdList as $merchantDataMapId) {
            $merchantMapId = $this->utilEncodingService->decodeJson($merchantDataMapId, true);
            if (isset($merchantMapId[static::KEY_ID_MERCHANT])) {
                $merchantIds[] = $merchantMapId[static::KEY_ID_MERCHANT];
            }
        }

        if ($merchantIds) {
            return $this->get($merchantIds);
        }

        return [];
    }

    /**
     * @param string $reference
     *
     * @return string
     */
    protected function generateKey(string $reference): string
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setReference($reference);
        $synchronizationDataTransfer->setStore($this->storeClient->getCurrentStore()->getName());

        return $this->synchronizationService
            ->getStorageKeyBuilder(MerchantStorageConfig::MERCHANT_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
