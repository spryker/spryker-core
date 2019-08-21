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
     * @param \Spryker\Client\CmsBlockStorage\Dependency\Client\CmsBlockStorageToStorageInterface $storageClient
     * @param \Spryker\Client\CmsBlockStorage\Dependency\Service\CmsBlockStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(CmsBlockStorageToStorageInterface $storageClient, CmsBlockStorageToSynchronizationServiceInterface $synchronizationService)
    {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param string[] $blockNames
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function getBlocksByNames(array $blockNames, $localeName, $storeName)
    {
        $searchKeys = [];

        foreach ($blockNames as $blockName) {
            $searchKeys[] = $this->generateKey($blockName, CmsBlockStorageConstants::CMS_BLOCK_RESOURCE_NAME, $localeName, $storeName);
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
     * @param string $localeName
     *
     * @return array
     */
    public function getBlockNamesByOptions(array $options, $localeName)
    {
        $availableBlockNames = null;

        foreach ($options as $optionKey => $resources) {
            $resources = (array)$resources;
            $blockNames = $this->getBlockNamesForOption($optionKey, $resources);

            $availableBlockNames = $availableBlockNames === null ?
                $blockNames :
                array_intersect($availableBlockNames, $blockNames);
        }

        return $availableBlockNames ?: [];
    }

    /**
     * @param array $options
     *
     * @return array
     */
    public function getBlockKeysByOptions(array $options): array
    {
        $position = null;

        if (isset($options[static::OPTION_POSITION])) {
            $position = $options[static::OPTION_POSITION] ?? null;
            unset($options[static::OPTION_POSITION]);
        }

        $searchKey = '';

        foreach ($options as $key => $value) {
            $searchKey = $this->generateKey($value, 'cms_block_' . $key);
        }

        $blocksData = $this->storageClient->get($searchKey);
        $blockKeys = [];

        if (isset($blocksData[static::ARRAY_KEY_CMS_BLOCK_CATEGORIES])) {
            foreach ($blocksData[static::ARRAY_KEY_CMS_BLOCK_CATEGORIES] as $key => $item) {
                if ($position && $item[static::OPTION_POSITION] === $position) {
                    $blockKeys = $item[static::ARRAY_KEY_BLOCK_KEYS];
                    break;
                }
            }

            return $blockKeys;
        }

        return $blocksData[static::ARRAY_KEY_BLOCK_KEYS];
    }

    /**
     * @param string $blockName
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function getMappingDataByBlockName(string $blockName, string $localeName, string $storeName): array
    {
        $blockNameKey = static::PREFIX_MAPPING_CMS_BLOCK_KEY . $this->generateBlockNameKey($blockName);
        $searchKey = $this->generateKey($blockNameKey, CmsBlockStorageConstants::CMS_BLOCK_RESOURCE_NAME, $localeName, $storeName);

        $mappingData = $this->storageClient->get($searchKey) ?: [];

        return array_filter($mappingData);
    }

    /**
     * @param string $optionKey
     * @param int[] $idResources
     *
     * @return string[]
     */
    protected function getBlockNamesForOption($optionKey, $idResources)
    {
        $searchKeys = [];

        foreach ($idResources as $id) {
            $searchKeys[] = $this->generateKey((string)$id, 'cms_block' . '_' . $optionKey);
        }

        $blockNames = $this->storageClient->getMulti($searchKeys);
        $blockNames = $this->decodeMultiResultToArrays($blockNames);
        $blockNames = $this->getBlockNamePositionArray($blockNames);

        return $blockNames;
    }

    /**
     * @param array $blockNames
     *
     * @return array
     */
    protected function getBlockNamePositionArray(array $blockNames)
    {
        $blockNames = array_values($blockNames);
        $blockOptionNames = [];

        foreach ($blockNames as $blockName) {
            if (!is_array($blockName)) {
                continue;
            }

            $blockOptionNames = $this->filterBlockByPosition($blockName, $blockOptionNames);
        }

        return $blockOptionNames;
    }

    /**
     * @param array $blockName
     * @param array $blockOptionNames
     *
     * @return array
     */
    protected function filterBlockByPosition(array $blockName, array $blockOptionNames)
    {
        foreach ($blockName as $item) {
            if (!is_array($item)) {
                $blockOptionNames[] = $item;

                continue;
            }

            $position = $item['position'];
            $positionBlocks = [];

            if (isset($blockOptionNames[$position])) {
                $positionBlocks = $blockOptionNames[$position];
            }

            $blockOptionNames[$position] = array_merge($item['block_names'], $positionBlocks);
        }

        return $blockOptionNames;
    }

    /**
     * @param array|null $array
     *
     * @return array
     */
    protected function decodeMultiResultToArrays($array)
    {
        if (!is_array($array)) {
            return [];
        }

        $array = array_filter($array);

        $resultArray = [];
        foreach ($array as $key => $result) {
            $resultArray = array_merge_recursive($resultArray, json_decode($result, true));
        }

        return $resultArray;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function generateBlockNameKey($name)
    {
        $charsToReplace = ['"', "'", ' ', "\0", "\n", "\r"];

        return str_replace($charsToReplace, '-', mb_strtolower(trim($name)));
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
}
