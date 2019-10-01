<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotStorage\Reader;

use Generated\Shared\Transfer\CmsSlotStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\CmsSlotStorage\Dependency\Client\CmsSlotStorageToStorageClientInterface;
use Spryker\Client\CmsSlotStorage\Dependency\Service\CmsSlotStorageToSynchronizationServiceInterface;
use Spryker\Shared\CmsSlotStorage\CmsSlotStorageConstants;

class CmsSlotStorageReader implements CmsSlotStorageReaderInterface
{
    /**
     * @var \Spryker\Client\CmsSlotStorage\Dependency\Client\CmsSlotStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\CmsSlotStorage\Dependency\Service\CmsSlotStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param \Spryker\Client\CmsSlotStorage\Dependency\Client\CmsSlotStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\CmsSlotStorage\Dependency\Service\CmsSlotStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        CmsSlotStorageToStorageClientInterface $storageClient,
        CmsSlotStorageToSynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param string $cmsSlotKey
     *
     * @return \Generated\Shared\Transfer\CmsSlotStorageTransfer|null
     */
    public function findSlotByKey(string $cmsSlotKey): ?CmsSlotStorageTransfer
    {
        $cmsSlotStorageData = $this->storageClient->get(
            $this->generateKey($cmsSlotKey)
        );

        if (!$cmsSlotStorageData) {
            return null;
        }

        return $this->mapToCmsSlotStorage($cmsSlotStorageData);
    }

    /**
     * @param array $cmsSlotStorageData
     *
     * @return \Generated\Shared\Transfer\CmsSlotStorageTransfer
     */
    protected function mapToCmsSlotStorage(array $cmsSlotStorageData): CmsSlotStorageTransfer
    {
        return (new CmsSlotStorageTransfer())
            ->fromArray($cmsSlotStorageData, true);
    }

    /**
     * @param string $cmsSlotKey
     *
     * @return string
     */
    protected function generateKey(string $cmsSlotKey): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference((string)$cmsSlotKey);

        return $this->synchronizationService
            ->getStorageKeyBuilder(CmsSlotStorageConstants::CMS_SLOT_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
