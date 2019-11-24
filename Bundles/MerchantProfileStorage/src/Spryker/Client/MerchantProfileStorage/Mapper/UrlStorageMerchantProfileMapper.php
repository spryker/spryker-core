<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProfileStorage\Mapper;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Generated\Shared\Transfer\UrlStorageResourceMapTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Client\MerchantProfileStorage\Dependency\Client\MerchantProfileStorageToStorageClientInterface;
use Spryker\Client\MerchantProfileStorage\Dependency\Service\MerchantProfileStorageConnectorToSynchronizationServiceInterface;
use Spryker\Shared\MerchantProfileStorage\MerchantProfileStorageConfig;

class UrlStorageMerchantProfileMapper implements UrlStorageMerchantProfileMapperInterface
{
    protected const KEY_ID_MERCHANT = 'id';
    protected const KEY_ID_MERCHANT_PROFILE = 'id_merchant_profile';

    /**
     * @var \Spryker\Client\MerchantProfileStorage\Dependency\Service\MerchantProfileStorageConnectorToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\MerchantProfileStorage\Dependency\Client\MerchantProfileStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @param \Spryker\Client\MerchantProfileStorage\Dependency\Service\MerchantProfileStorageConnectorToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\MerchantProfileStorage\Dependency\Client\MerchantProfileStorageToStorageClientInterface $storageClient
     */
    public function __construct(
        MerchantProfileStorageConnectorToSynchronizationServiceInterface $synchronizationService,
        MerchantProfileStorageToStorageClientInterface $storageClient
    ) {
        $this->synchronizationService = $synchronizationService;
        $this->storageClient = $storageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     *
     * @return \Generated\Shared\Transfer\UrlStorageResourceMapTransfer
     */
    public function mapUrlStorageTransferToUrlStorageResourceMapTransfer(UrlStorageTransfer $urlStorageTransfer): UrlStorageResourceMapTransfer
    {
        $urlStorageResourceMapTransfer = new UrlStorageResourceMapTransfer();
        $idMerchantProfile = $urlStorageTransfer->getFkResourceMerchantProfile();

        if ($idMerchantProfile === null) {
            return $urlStorageResourceMapTransfer;
        }

        $merchantProfileMap = $this->storageClient->get(
            $this->generateKey(static::KEY_ID_MERCHANT_PROFILE . ':' . $idMerchantProfile)
        );

        if (isset($merchantProfileMap[static::KEY_ID_MERCHANT])) {
            $resourceKey = $this->generateKey($merchantProfileMap[static::KEY_ID_MERCHANT]);
            $urlStorageResourceMapTransfer->setResourceKey($resourceKey);
            $urlStorageResourceMapTransfer->setType(MerchantProfileStorageConfig::MERCHANT_PROFILE_RESOURCE_NAME);
        }

        return $urlStorageResourceMapTransfer;
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

        return $this->synchronizationService->getStorageKeyBuilder(MerchantProfileStorageConfig::MERCHANT_PROFILE_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
