<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockStorage\Storage;

use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockConditionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockTransfer;
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
     * @return \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer
     */
    public function getCmsSlotBlockCollection(
        string $cmsSlotTemplatePath,
        string $cmsSlotKey
    ): CmsSlotBlockCollectionTransfer {
        $storageKey = $this->generateStorageKey($cmsSlotTemplatePath, $cmsSlotKey);
        $cmsSlotBlockStorageData = $this->storageClient->get($storageKey);

        if (!$cmsSlotBlockStorageData) {
            return new CmsSlotBlockCollectionTransfer();
        }

        $cmsSlotBlockCollectionTransfer = new CmsSlotBlockCollectionTransfer();
        foreach ($cmsSlotBlockStorageData[CmsSlotBlockCollectionTransfer::CMS_SLOT_BLOCKS] as $cmsSlotBlock) {
            $cmsSlotBlockTransfer = (new CmsSlotBlockTransfer())
                ->setCmsBlockKey($cmsSlotBlock[CmsSlotBlockTransfer::CMS_BLOCK_KEY]);

            if ($cmsSlotBlock[CmsSlotBlockTransfer::CONDITIONS]) {
                foreach ($cmsSlotBlock[CmsSlotBlockTransfer::CONDITIONS] as $conditionKey => $condition) {
                    $cmsSlotBlockTransfer->addCondition(
                        $conditionKey,
                        (new CmsSlotBlockConditionTransfer())->fromArray($condition, true)
                    );
                }
            }

            $cmsSlotBlockCollectionTransfer->addCmsSlotBlock($cmsSlotBlockTransfer);
        }

        return $cmsSlotBlockCollectionTransfer;
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
