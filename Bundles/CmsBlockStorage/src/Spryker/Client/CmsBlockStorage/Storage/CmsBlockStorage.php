<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlockStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\CmsBlockStorage\Dependency\Client\CmsBlockStorageToStorageInterface;
use Spryker\Client\CmsBlockStorage\Dependency\Service\CmsBlockStorageToSynchronizationServiceInterface;
use Spryker\Shared\CmsBlockStorage\CmsBlockStorageConstants;

class CmsBlockStorage implements CmsBlockStorageInterface
{
    protected const PREFIX_MAPPING_CMS_BLOCK_KEY = 'name:';
    protected const ARRAY_KEY_CMS_BLOCK_CATEGORIES = 'cms_block_categories';
    protected const ARRAY_KEY_BLOCK_KEYS = 'block_keys';
    protected const OPTION_CATEGORY = 'category';
    protected const OPTION_POSITION = 'position';

    /**
     * @var \Spryker\Client\CmsBlockStorage\Dependency\Client\CmsBlockStorageToStorageInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\CmsBlockStorage\Dependency\Service\CmsBlockStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Zed\CmsBlockStorageExtension\Dependency\Plugin\CmsBlockStorageRelatedBlocksFinderPluginInterface[]
     */
    protected $cmsBlockStorageRelatedBlocksFinderPlugins;

    /**
     * @param \Spryker\Client\CmsBlockStorage\Dependency\Client\CmsBlockStorageToStorageInterface $storageClient
     * @param \Spryker\Client\CmsBlockStorage\Dependency\Service\CmsBlockStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Zed\CmsBlockStorageExtension\Dependency\Plugin\CmsBlockStorageRelatedBlocksFinderPluginInterface[] $cmsBlockStorageRelatedBlocksFinderPlugins
     */
    public function __construct(
        CmsBlockStorageToStorageInterface $storageClient,
        CmsBlockStorageToSynchronizationServiceInterface $synchronizationService,
        array $cmsBlockStorageRelatedBlocksFinderPlugins
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->cmsBlockStorageRelatedBlocksFinderPlugins = $cmsBlockStorageRelatedBlocksFinderPlugins;
    }

    /**
     * @deprecated Use \Spryker\Client\CmsBlockStorage\Storage\CmsBlockStorage::getBlocksByKeys() instead.
     *
     * @param string[] $blockNames
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function getBlocksByNames(array $blockNames, $localeName, $storeName): array
    {
        $searchKeys = [];

        foreach ($blockNames as $blockName) {
            $mappingData = $this->getMappingDataByBlockName($blockName, $localeName, $storeName);

            if (count($mappingData) < 1) {
                continue;
            }

            $blockKey = $mappingData['id'];

            $searchKeys[] = $this->generateKey($blockKey, CmsBlockStorageConstants::CMS_BLOCK_RESOURCE_NAME, $localeName, $storeName);
        }

        $resultArray = $this->storageClient->getMulti($searchKeys) ?: [];
        $resultArray = array_filter($resultArray);

        $blocks = [];
        foreach ($resultArray as $key => $result) {
            $blocks[] = json_decode($result, true);
        }

        return $blocks;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    public function getBlockKeysByOptions(array $options): array
    {
        $blockKeys = [];

        foreach ($this->cmsBlockStorageRelatedBlocksFinderPlugins as $cmsBlockStorageRelatedBlocksFinderPlugin) {
            $cmsBlockTransfers = $cmsBlockStorageRelatedBlocksFinderPlugin->findRelatedCmsBlocks($options);

            if (count($cmsBlockTransfers) < 1) {
                continue;
            }

            $blockKeys = array_merge($blockKeys, $this->getBlockKeysFromTransfers($cmsBlockTransfers));
        }

        return $blockKeys;
    }

    /**
     * @param string[] $blockKeys
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function getBlocksByKeys(array $blockKeys, string $localeName, string $storeName): array
    {
        $storageKeys = [];

        foreach ($blockKeys as $blockKey) {
            $storageKeys[] = $this->generateKey(
                $blockKey,
                CmsBlockStorageConstants::CMS_BLOCK_RESOURCE_NAME,
                $localeName,
                $storeName
            );
        }

        $resultArray = $this->storageClient->getMulti($storageKeys) ?: [];
        $resultArray = array_filter($resultArray);

        $blocks = [];

        foreach ($resultArray as $key => $result) {
            $blocks[] = json_decode($result, true);
        }

        return $blocks;
    }

    /**
     * @param string $blockName
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    protected function getMappingDataByBlockName(string $blockName, string $localeName, string $storeName): array
    {
        $blockNameKey = static::PREFIX_MAPPING_CMS_BLOCK_KEY . $blockName;
        $searchKey = $this->generateKey($blockNameKey, CmsBlockStorageConstants::CMS_BLOCK_RESOURCE_NAME, $localeName, $storeName);

        $mappingData = $this->storageClient->get($searchKey) ?: [];

        return array_filter($mappingData);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer[] $cmsBlockTransfers
     *
     * @return array
     */
    protected function getBlockKeysFromTransfers(array $cmsBlockTransfers): array
    {
        $blockKeys = [];

        foreach ($cmsBlockTransfers as $cmsBlockTransfer) {
            $blockKeys[] = $cmsBlockTransfer->getKey();
        }

        return $blockKeys;
    }

    /**
     * @param string $blockKey
     * @param string $resourceName
     * @param string|null $localeName
     * @param string|null $storeName
     *
     * @return string
     */
    protected function generateKey(
        string $blockKey,
        string $resourceName = CmsBlockStorageConstants::CMS_BLOCK_RESOURCE_NAME,
        ?string $localeName = null,
        ?string $storeName = null
    ): string {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setStore($storeName);
        $synchronizationDataTransfer->setLocale($localeName);
        $synchronizationDataTransfer->setReference($blockKey);

        return $this->synchronizationService->getStorageKeyBuilder($resourceName)->generateKey($synchronizationDataTransfer);
    }
}
