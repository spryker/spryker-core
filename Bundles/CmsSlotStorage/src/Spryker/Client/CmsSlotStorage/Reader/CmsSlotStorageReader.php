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
use Spryker\Client\CmsSlotStorage\Exception\CmsSlotNotFoundException;
use Spryker\Shared\CmsSlotStorage\CmsSlotStorageConfig;

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
     * @throws \Spryker\Client\CmsSlotStorage\Exception\CmsSlotNotFoundException
     *
     * @return \Generated\Shared\Transfer\CmsSlotStorageTransfer
     */
    public function getCmsSlotByKey(string $cmsSlotKey): CmsSlotStorageTransfer
    {
        $cmsSlotStorageData = $this->storageClient->get(
            $this->generateKey($cmsSlotKey)
        );

        if (!$cmsSlotStorageData) {
            throw new CmsSlotNotFoundException(sprintf(
                'CMS Slot key "%s" not found.',
                $cmsSlotKey
            ));
        }

        return $this->mapToCmsSlotStorageTransfer($cmsSlotStorageData);
    }

    /**
     * @param array $cmsSlotStorageData
     *
     * @return \Generated\Shared\Transfer\CmsSlotStorageTransfer
     */
    protected function mapToCmsSlotStorageTransfer(array $cmsSlotStorageData): CmsSlotStorageTransfer
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
            ->getStorageKeyBuilder(CmsSlotStorageConfig::CMS_SLOT_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
