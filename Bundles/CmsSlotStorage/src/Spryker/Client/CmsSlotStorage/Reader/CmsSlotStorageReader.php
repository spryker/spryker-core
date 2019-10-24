<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotStorage\Reader;

use Generated\Shared\Transfer\CmsSlotTransfer;
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
     * @return \Generated\Shared\Transfer\CmsSlotTransfer|null
     */
    public function findCmsSlotByKey(string $cmsSlotKey): ?CmsSlotTransfer
    {
        $cmsSlotStorageData = $this->storageClient->get(
            $this->generateKey($cmsSlotKey)
        );

        if (!$cmsSlotStorageData) {
            return null;
        }

        return $this->mapToCmsSlotTransfer($cmsSlotStorageData);
    }

    /**
     * @param array $cmsSlotStorageData
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer
     */
    protected function mapToCmsSlotTransfer(array $cmsSlotStorageData): CmsSlotTransfer
    {
        return (new CmsSlotTransfer())
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
