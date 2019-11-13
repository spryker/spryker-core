<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockStorage\Storage;

use Generated\Shared\Transfer\CmsSlotBlockStorageDataTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\CmsSlotBlockStorage\Dependency\Client\CmsSlotBlockStorageToStorageClientInterface;
use Spryker\Client\CmsSlotBlockStorage\Dependency\Service\CmsSlotBlockStorageToSynchronizationServiceInterface;
use Spryker\Service\CmsSlotBlockStorage\CmsSlotBlockStorageServiceInterface;
use Spryker\Shared\CmsSlotBlockStorage\CmsSlotBlockStorageConfig;

class CmsSlotBlockStorageReader implements CmsSlotBlockStorageReaderInterface
{
    /**
     * @var \Spryker\Client\CmsSlotBlockStorage\Dependency\Client\CmsSlotBlockStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Service\CmsSlotBlockStorage\CmsSlotBlockStorageServiceInterface
     */
    protected $cmsSlotBlockStorageService;

    /**
     * @var \Spryker\Client\CmsSlotBlockStorage\Dependency\Service\CmsSlotBlockStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param \Spryker\Client\CmsSlotBlockStorage\Dependency\Client\CmsSlotBlockStorageToStorageClientInterface $storageClient
     * @param \Spryker\Service\CmsSlotBlockStorage\CmsSlotBlockStorageServiceInterface $cmsSlotBlockStorageService
     * @param \Spryker\Client\CmsSlotBlockStorage\Dependency\Service\CmsSlotBlockStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        CmsSlotBlockStorageToStorageClientInterface $storageClient,
        CmsSlotBlockStorageServiceInterface $cmsSlotBlockStorageService,
        CmsSlotBlockStorageToSynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->cmsSlotBlockStorageService = $cmsSlotBlockStorageService;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param string $cmsSlotTemplatePath
     * @param string $cmsSlotKey
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockStorageDataTransfer|null
     */
    public function findCmsSlotBlockStorageData(
        string $cmsSlotTemplatePath,
        string $cmsSlotKey
    ): ?CmsSlotBlockStorageDataTransfer {
        $storageKey = $this->generateStorageKey($cmsSlotTemplatePath, $cmsSlotKey);
        $cmsSlotBlockStorageData = $this->storageClient->get($storageKey);

        if (!$cmsSlotBlockStorageData) {
            return null;
        }

        $cmsSlotBlockStorageDataTransfer = new CmsSlotBlockStorageDataTransfer();
        $cmsSlotBlockStorageDataTransfer->fromArray($cmsSlotBlockStorageData, true);

        return $cmsSlotBlockStorageDataTransfer;
    }

    /**
     * @param string $cmsSlotTemplatePath
     * @param string $cmsSlotKey
     *
     * @return string
     */
    protected function generateStorageKey(string $cmsSlotTemplatePath, string $cmsSlotKey): string
    {
        $slotTemplateKey = $this->cmsSlotBlockStorageService->generateSlotTemplateKey($cmsSlotTemplatePath, $cmsSlotKey);
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference($slotTemplateKey);

        return $this->synchronizationService
            ->getStorageKeyBuilder(CmsSlotBlockStorageConfig::CMS_SLOT_BLOCK_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
