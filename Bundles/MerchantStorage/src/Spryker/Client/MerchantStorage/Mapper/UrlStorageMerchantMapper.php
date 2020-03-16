<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantStorage\Mapper;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Generated\Shared\Transfer\UrlStorageResourceMapTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Client\MerchantStorage\Dependency\Client\MerchantStorageToStorageClientInterface;
use Spryker\Client\MerchantStorage\Dependency\Service\MerchantStorageConnectorToSynchronizationServiceInterface;
use Spryker\Shared\MerchantStorage\MerchantStorageConfig;

class UrlStorageMerchantMapper implements UrlStorageMerchantMapperInterface
{
    /**
     * @var \Spryker\Client\MerchantStorage\Dependency\Service\MerchantStorageConnectorToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\MerchantStorage\Dependency\Client\MerchantStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @param \Spryker\Client\MerchantStorage\Dependency\Service\MerchantStorageConnectorToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\MerchantStorage\Dependency\Client\MerchantStorageToStorageClientInterface $storageClient
     */
    public function __construct(
        MerchantStorageConnectorToSynchronizationServiceInterface $synchronizationService,
        MerchantStorageToStorageClientInterface $storageClient
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
        $idMerchant = $urlStorageTransfer->getFkResourceMerchant();

        if ($idMerchant === null) {
            return $urlStorageResourceMapTransfer;
        }

        $resourceKey = $this->generateKey((string)$idMerchant);
        $urlStorageResourceMapTransfer->setResourceKey($resourceKey);
        $urlStorageResourceMapTransfer->setType(MerchantStorageConfig::MERCHANT_RESOURCE_NAME);

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

        return $this->synchronizationService->getStorageKeyBuilder(MerchantStorageConfig::MERCHANT_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
