<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductStorage\Business\Storage;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Zed\CmsBlockProductStorage\Dependency\Client\CmsBlockProductStorageToStorageClientInterface;
use Spryker\Zed\CmsBlockProductStorage\Dependency\Service\CmsBlockProductStorageToSynchronizationServiceInterface;

class CmsBlockProductStorageReader implements CmsBlockProductStorageReaderInterface
{
    protected const OPTION_KEY_PRODUCT = 'product';
    protected const RESOURCE_CMS_BLOCK_PRODUCT = 'cms_block_product';
    protected const KEY_BLOCK_KEYS = 'block_keys';

    /**
     * @var \Spryker\Zed\CmsBlockProductStorage\Dependency\Client\CmsBlockProductStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Zed\CmsBlockProductStorage\Dependency\Service\CmsBlockProductStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param \Spryker\Zed\CmsBlockProductStorage\Dependency\Client\CmsBlockProductStorageToStorageClientInterface $storageClient
     * @param \Spryker\Zed\CmsBlockProductStorage\Dependency\Service\CmsBlockProductStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        CmsBlockProductStorageToStorageClientInterface $storageClient,
        CmsBlockProductStorageToSynchronizationServiceInterface $synchronizationService
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
        if (!isset($options[static::OPTION_KEY_PRODUCT])) {
            return [];
        }

        $searchKey = $this->generateKey($options[static::OPTION_KEY_PRODUCT], static::RESOURCE_CMS_BLOCK_PRODUCT);

        $block = $this->storageClient->get($searchKey);

        if (!$block) {
            return [];
        }

        return $this->mapBlockKeysArrayToCmsBlockTransfers($block[static::KEY_BLOCK_KEYS]);
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
