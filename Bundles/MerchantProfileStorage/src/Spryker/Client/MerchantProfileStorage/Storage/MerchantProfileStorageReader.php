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
     * @param \Spryker\Client\MerchantProfileStorage\Mapper\MerchantProfileStorageMapperInterface $merchantProfileStorageMapper
     * @param \Spryker\Client\MerchantProfileStorage\Dependency\Service\MerchantProfileStorageConnectorToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\MerchantProfileStorage\Dependency\Client\MerchantProfileStorageToStorageClientInterface $storageClient
     */
    public function __construct(
        MerchantProfileStorageMapperInterface $merchantProfileStorageMapper,
        MerchantProfileStorageConnectorToSynchronizationServiceInterface $synchronizationService,
        MerchantProfileStorageToStorageClientInterface $storageClient
    ) {
        $this->merchantProfileStorageMapper = $merchantProfileStorageMapper;
        $this->synchronizationService = $synchronizationService;
        $this->storageClient = $storageClient;
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
