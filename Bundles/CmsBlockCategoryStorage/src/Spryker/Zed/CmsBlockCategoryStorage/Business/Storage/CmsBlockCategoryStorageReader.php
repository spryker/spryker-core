<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Business\Storage;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Zed\CmsBlockCategoryStorage\Dependency\Client\CmsBlockCategoryStorageToStorageClientInterface;
use Spryker\Zed\CmsBlockCategoryStorage\Dependency\Service\CmsBlockCategoryStorageToSynchronizationServiceInterface;

class CmsBlockCategoryStorageReader implements CmsBlockCategoryStorageReaderInterface
{
    protected const OPTION_KEY_CATEGORY = 'category';
    protected const OPTION_KEY_POSITION = 'position';
    protected const RESOURCE_CMS_BLOCK_CATEGORY = 'cms_block_category';
    protected const KEY_CMS_BLOCK_CATEGORIES = 'cms_block_categories';
    protected const KEY_BLOCK_KEYS = 'block_keys';

    /**
     * @var \Spryker\Zed\CmsBlockCategoryStorage\Dependency\Client\CmsBlockCategoryStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Zed\CmsBlockCategoryStorage\Dependency\Service\CmsBlockCategoryStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param \Spryker\Zed\CmsBlockCategoryStorage\Dependency\Client\CmsBlockCategoryStorageToStorageClientInterface $storageClient
     * @param \Spryker\Zed\CmsBlockCategoryStorage\Dependency\Service\CmsBlockCategoryStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        CmsBlockCategoryStorageToStorageClientInterface $storageClient,
        CmsBlockCategoryStorageToSynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function findCmsBlocksByOptions(array $options): array
    {
        if (!isset($options[static::OPTION_KEY_CATEGORY])) {
            return [];
        }

        $position = $options[static::OPTION_KEY_POSITION] ?? null;
        $searchKey = $this->generateKey($options[static::OPTION_KEY_CATEGORY], static::RESOURCE_CMS_BLOCK_CATEGORY);

        $blocks = $this->storageClient->get($searchKey);
        $cmsBlockTransfers = [];

        foreach ($blocks[static::KEY_CMS_BLOCK_CATEGORIES] as $blockData) {
            if ($position && $blockData[static::OPTION_KEY_POSITION] === $position) {
                $cmsBlockTransfers = $this->mapBlockKeysArrayToCmsBlockTransfers($blockData[static::KEY_BLOCK_KEYS]);
                break;
            }
        }

        return $cmsBlockTransfers;
    }

    /**
     * @param string $reference
     * @param string $resourceName
     * @param string|null $localeName
     * @param string|null $storeName
     *
     * @return string
     */
    protected function generateKey(
        string $reference,
        string $resourceName,
        ?string $localeName = null,
        ?string $storeName = null
    ): string {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setStore($storeName);
        $synchronizationDataTransfer->setLocale($localeName);
        $synchronizationDataTransfer->setReference($reference);

        return $this->synchronizationService->getStorageKeyBuilder($resourceName)->generateKey($synchronizationDataTransfer);
    }

    /**
     * @param array $blockKeys
     *
     * @return array
     */
    protected function mapBlockKeysArrayToCmsBlockTransfers(array $blockKeys): array
    {
        $cmsBlockTransfers = [];

        foreach ($blockKeys as $blockKey) {
            $cmsBlockTransfers[] = (new CmsBlockTransfer())->setKey($blockKey);
        }

        return $cmsBlockTransfers;
    }
}
