<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProfileStorage\Storage;

use Generated\Shared\Transfer\MerchantProfileStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\MerchantProfileStorage\Dependency\Client\MerchantProfileStorageToStorageClientInterface;
use Spryker\Client\MerchantProfileStorage\Dependency\Service\MerchantProfileStorageConnectorToSynchronizationServiceInterface;
use Spryker\Client\MerchantProfileStorage\Dependency\Service\MerchantProfileStorageToUtilEncodingServiceInterface;
use Spryker\Client\MerchantProfileStorage\Mapper\MerchantProfileStorageMapperInterface;
use Spryker\Shared\MerchantProfileStorage\MerchantProfileStorageConfig;

class MerchantProfileStorageReader implements MerchantProfileStorageReaderInterface
{
    /**
     * @var \Spryker\Client\MerchantProfileStorage\Mapper\MerchantProfileStorageMapperInterface
     */
    protected $merchantProfileStorageMapper;

    /**
     * @var \Spryker\Client\MerchantProfileStorage\Dependency\Service\MerchantProfileStorageConnectorToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\MerchantProfileStorage\Dependency\Client\MerchantProfileStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\MerchantProfileStorage\Dependency\Service\MerchantProfileStorageToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Client\MerchantProfileStorage\Mapper\MerchantProfileStorageMapperInterface $merchantProfileStorageMapper
     * @param \Spryker\Client\MerchantProfileStorage\Dependency\Service\MerchantProfileStorageConnectorToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\MerchantProfileStorage\Dependency\Client\MerchantProfileStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\MerchantProfileStorage\Dependency\Service\MerchantProfileStorageToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        MerchantProfileStorageMapperInterface $merchantProfileStorageMapper,
        MerchantProfileStorageConnectorToSynchronizationServiceInterface $synchronizationService,
        MerchantProfileStorageToStorageClientInterface $storageClient,
        MerchantProfileStorageToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->merchantProfileStorageMapper = $merchantProfileStorageMapper;
        $this->synchronizationService = $synchronizationService;
        $this->storageClient = $storageClient;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantProfileStorageTransfer|null
     */
    public function findMerchantProfileStorageData(int $idMerchant): ?MerchantProfileStorageTransfer
    {
        $merchantProfileKey = $this->generateKey($idMerchant);
        $merchantProfileData = $this->storageClient->get($merchantProfileKey);
        if (empty($merchantProfileData)) {
            return null;
        }

        return $this->merchantProfileStorageMapper->mapMerchantProfileStorageDataToMerchantProfileStorageTransfer($merchantProfileData);
    }

    /**
     * @param int[] $merchantIds
     *
     * @return \Generated\Shared\Transfer\MerchantProfileStorageTransfer[]
     */
    public function findMerchantProfileStorageList(array $merchantIds): array
    {
        $merchantProfileDataCollection = [];

        $merchantProfileKeys = array_map(function ($idMerchant) {
            return $this->generateKey($idMerchant);
        }, $merchantIds);

        $merchantProfileDataList = $this->storageClient->getMulti($merchantProfileKeys);

        foreach ($merchantProfileDataList as $merchantProfileData) {
            if ($merchantProfileData === null) {
                continue;
            }
            $merchantProfileDataCollection[] = $this->merchantProfileStorageMapper->mapMerchantProfileStorageDataToMerchantProfileStorageTransfer(
                $this->utilEncodingService->decodeJson($merchantProfileData, true)
            );
        }

        return $merchantProfileDataCollection;
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
            ->getStorageKeyBuilder(MerchantProfileStorageConfig::MERCHANT_PROFILE_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
