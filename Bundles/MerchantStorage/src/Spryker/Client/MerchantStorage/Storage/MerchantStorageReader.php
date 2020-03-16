<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantStorage\Storage;

use Generated\Shared\Transfer\MerchantStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\MerchantStorage\Dependency\Client\MerchantStorageToStorageClientInterface;
use Spryker\Client\MerchantStorage\Dependency\Service\MerchantStorageConnectorToSynchronizationServiceInterface;
use Spryker\Client\MerchantStorage\Dependency\Service\MerchantStorageToUtilEncodingServiceInterface;
use Spryker\Client\MerchantStorage\Mapper\MerchantStorageMapperInterface;
use Spryker\Shared\MerchantStorage\MerchantStorageConfig;

class MerchantStorageReader implements MerchantStorageReaderInterface
{
    /**
     * @var \Spryker\Client\MerchantStorage\Mapper\MerchantStorageMapperInterface
     */
    protected $merchantStorageMapper;

    /**
     * @var \Spryker\Client\MerchantStorage\Dependency\Service\MerchantStorageConnectorToSynchronizationServiceInterface
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
     * @param \Spryker\Client\MerchantStorage\Mapper\MerchantStorageMapperInterface $merchantStorageMapper
     * @param \Spryker\Client\MerchantStorage\Dependency\Service\MerchantStorageConnectorToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\MerchantStorage\Dependency\Client\MerchantStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\MerchantStorage\Dependency\Service\MerchantStorageToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        MerchantStorageMapperInterface $merchantStorageMapper,
        MerchantStorageConnectorToSynchronizationServiceInterface $synchronizationService,
        MerchantStorageToStorageClientInterface $storageClient,
        MerchantStorageToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->merchantStorageMapper = $merchantStorageMapper;
        $this->synchronizationService = $synchronizationService;
        $this->storageClient = $storageClient;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer|null
     */
    public function findMerchantStorageData(int $idMerchant): ?MerchantStorageTransfer
    {
        $merchantKey = $this->generateKey($idMerchant);
        $merchantData = $this->storageClient->get($merchantKey);
        if (empty($merchantData)) {
            return null;
        }

        return $this->merchantStorageMapper->mapMerchantStorageDataToMerchantStorageTransfer($merchantData);
    }

    /**
     * @param int[] $merchantIds
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer[]
     */
    public function findMerchantStorageList(array $merchantIds): array
    {
        $merchantDataCollection = [];

        $merchantKeys = array_map(function ($idMerchant) {
            return $this->generateKey($idMerchant);
        }, $merchantIds);

        $merchantDataList = $this->storageClient->getMulti($merchantKeys);

        foreach ($merchantDataList as $merchantData) {
            if ($merchantData === null) {
                continue;
            }
            $merchantDataCollection[] = $this->merchantStorageMapper->mapMerchantStorageDataToMerchantStorageTransfer(
                $this->utilEncodingService->decodeJson($merchantData, true)
            );
        }

        return $merchantDataCollection;
    }

    /**
     * @param int $idMerchant
     *
     * @return string
     */
    protected function generateKey(int $idMerchant): string
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setReference((string)$idMerchant);

        return $this->synchronizationService
            ->getStorageKeyBuilder(MerchantStorageConfig::MERCHANT_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
