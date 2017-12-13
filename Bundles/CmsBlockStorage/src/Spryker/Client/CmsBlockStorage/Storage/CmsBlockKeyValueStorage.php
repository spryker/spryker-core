<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlockStorage\Storage;

use Generated\Shared\Transfer\SpyCmsBlockTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\CmsBlockStorage\Dependency\Client\CmsBlockStorageToStorageInterface;
use Spryker\Client\CmsBlockStorage\Dependency\Service\CmsBlockStorageToSynchronizationServiceInterface;
use Spryker\Client\CmsBlockStorage\Dependency\Service\CmsBlockStorageToUtilSynchronizationServiceInterface;
use Spryker\Shared\CmsBlockStorage\CmsBlockStorageConstants;
use Spryker\Shared\Synchronization\SynchronizationConfig;

class CmsBlockKeyValueStorage implements CmsBlockKeyValueStorageInterface
{

    /**
     * @var \Spryker\Client\CmsBlockStorage\Dependency\Client\CmsBlockStorageToStorageInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\CmsBlockStorage\Dependency\Service\CmsBlockStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\CmsBlockStorage\Dependency\Service\CmsBlockStorageToUtilSynchronizationServiceInterface
     */
    protected $utilSynchronizationService;

    /**
     * @param \Spryker\Client\CmsBlockStorage\Dependency\Client\CmsBlockStorageToStorageInterface $storageClient
     * @param \Spryker\Client\CmsBlockStorage\Dependency\Service\CmsBlockStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\CmsBlockStorage\Dependency\Service\CmsBlockStorageToUtilSynchronizationServiceInterface $utilSynchronizationService
     */
    public function __construct(CmsBlockStorageToStorageInterface $storageClient, CmsBlockStorageToSynchronizationServiceInterface $synchronizationService, CmsBlockStorageToUtilSynchronizationServiceInterface $utilSynchronizationService)
    {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->utilSynchronizationService = $utilSynchronizationService;
    }

    /**
     * @param string $blockKey
     *
     * @return \Generated\Shared\Transfer\SpyCmsBlockTransfer
     */
    public function getBlockByKey($blockKey)
    {
        $key = $this->generateBlockKey($blockKey);
        $blockArray = $this->storageClient->get($key, SynchronizationConfig::SYNCHRONIZATION_STORAGE_PREFIX);

        if ($blockArray === null) {
            return null;
        }

        return (new SpyCmsBlockTransfer())->fromArray($blockArray, true);
    }

    /**
     * @param string $blockKey
     *
     * @return string
     */
    protected function generateBlockKey($blockKey)
    {
        $blockName = $this->utilSynchronizationService->escapeKey($blockKey);
        $keyBuilder = $this->synchronizationService->getStorageKeyBuilder(CmsBlockStorageConstants::CMS_BLOCK_RESOURCE_NAME);

        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
                ->setReference($blockName);

        return $keyBuilder->generateKey($synchronizationDataTransfer);
    }

}
