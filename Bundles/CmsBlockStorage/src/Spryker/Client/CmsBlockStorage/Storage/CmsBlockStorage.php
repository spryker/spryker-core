<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlockStorage\Storage;

use Generated\Shared\Transfer\CmsBlockRequestTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\CmsBlockStorage\Dependency\Client\CmsBlockStorageToStorageInterface;
use Spryker\Client\CmsBlockStorage\Dependency\Service\CmsBlockStorageToSynchronizationServiceInterface;
use Spryker\Client\CmsBlockStorage\Dependency\Service\CmsBlockStorageToUtilEncodingServiceInterface;
use Spryker\Shared\CmsBlockStorage\CmsBlockStorageConstants;

class CmsBlockStorage implements CmsBlockStorageInterface
{
    protected const OPTION_NAME = 'name';
    protected const OPTION_KEY = 'key';
    protected const OPTION_KEYS = 'keys';

    protected const PREFIX_MAPPING_CMS_BLOCK_KEY = 'name:';

    /**
     * @var \Spryker\Client\CmsBlockStorage\Dependency\Client\CmsBlockStorageToStorageInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\CmsBlockStorage\Dependency\Service\CmsBlockStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\CmsBlockStorage\Dependency\Service\CmsBlockStorageToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Client\CmsBlockStorageExtension\Dependency\Plugin\CmsBlockStorageReaderPluginInterface[]
     */
    protected $cmsBlockStorageReaderPlugins;

    /**
     * @param \Spryker\Client\CmsBlockStorage\Dependency\Client\CmsBlockStorageToStorageInterface $storageClient
     * @param \Spryker\Client\CmsBlockStorage\Dependency\Service\CmsBlockStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\CmsBlockStorage\Dependency\Service\CmsBlockStorageToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Client\CmsBlockStorageExtension\Dependency\Plugin\CmsBlockStorageReaderPluginInterface[] $cmsBlockStorageReaderPlugins
     */
    public function __construct(
        CmsBlockStorageToStorageInterface $storageClient,
        CmsBlockStorageToSynchronizationServiceInterface $synchronizationService,
        CmsBlockStorageToUtilEncodingServiceInterface $utilEncodingService,
        array $cmsBlockStorageReaderPlugins
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->utilEncodingService = $utilEncodingService;
        $this->cmsBlockStorageReaderPlugins = $cmsBlockStorageReaderPlugins;
    }

    /**
     * @param array $options
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function getCmsBlocksByOptions(array $options, string $localeName, string $storeName): array
    {
        $cmsBlockKey = $options[static::OPTION_KEY] ?? null;
        $cmsBlockKeys = $options[static::OPTION_KEYS] ?? [];

        if ($cmsBlockKey) {
            $cmsBlockKeys = array_merge($cmsBlockKeys, [$cmsBlockKey]);
        }

        if ($cmsBlockKeys) {
            return $this->getBlocksByKeys($cmsBlockKeys, $localeName, $storeName);
        }

        $cmsBlockName = $options[static::OPTION_NAME] ?? null;

        if ($cmsBlockName) {
            return $this->getBlocksByNames([$cmsBlockName], $localeName, $storeName);
        }

        $availableBlockKeys = $this->getBlockKeysByOptions($options);

        return $this->getBlocksByKeys($availableBlockKeys, $localeName, $storeName);
    }

    /**
     * @param string[] $blockNames
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function getBlocksByNames(array $blockNames, $localeName, $storeName): array
    {
        $blockKeys = $this->getBlockKeysByNames($blockNames, $localeName, $storeName);

        return $this->getBlocksByKeys($blockKeys, $localeName, $storeName);
    }

    /**
     * @param string[] $blockKeys
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    protected function getBlocksByKeys(array $blockKeys, string $localeName, string $storeName): array
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

        $results = $this->storageClient->getMulti($storageKeys) ?: [];
        $results = array_values(array_filter($results));

        $blocks = array_map(function ($result) {
            return $this->utilEncodingService->decodeJson($result, true);
        }, $results);

        return $blocks;
    }

    /**
     * @param string[] $blockNames
     * @param string $localeName
     * @param string $storeName
     *
     * @return string[]|null
     */
    protected function getBlockKeysByNames(array $blockNames, $localeName, $storeName): ?array
    {
        $blockNameStorageKeys = [];
        foreach ($blockNames as $blockName) {
            $blockNameKey = static::PREFIX_MAPPING_CMS_BLOCK_KEY . $blockName;
            $blockNameStorageKeys[] = $this->generateKey(
                $blockNameKey,
                CmsBlockStorageConstants::CMS_BLOCK_RESOURCE_NAME,
                $localeName,
                $storeName
            );
        }

        $results = $this->storageClient->getMulti($blockNameStorageKeys);
        $results = array_values(array_filter($results));

        $blockKeys = array_map(function ($result) {
            $mappingData = $this->utilEncodingService->decodeJson($result, true);

            return $mappingData['id'] ?? null;
        }, $results);

        return $blockKeys;
    }

    /**
     * @param array $options
     *
     * @return string[]
     */
    protected function getBlockKeysByOptions(array $options): array
    {
        $blockKeys = [];

        $cmsBlockRequestTransfer = $this->mapOptionsToTransfer($options);

        foreach ($this->cmsBlockStorageReaderPlugins as $cmsBlockStorageReaderPlugin) {
            $cmsBlockTransfers = $cmsBlockStorageReaderPlugin->getCmsBlocks($cmsBlockRequestTransfer);

            if (count($cmsBlockTransfers) < 1) {
                continue;
            }

            $blockKeys = array_merge($blockKeys, $this->getBlockKeysFromTransfers($cmsBlockTransfers));
        }

        return $blockKeys;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer[] $cmsBlockTransfers
     *
     * @return string[]
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
     * @param string $localeName
     * @param string $storeName
     *
     * @return string
     */
    protected function generateKey(string $blockKey, string $resourceName, string $localeName, string $storeName): string
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setStore($storeName);
        $synchronizationDataTransfer->setLocale($localeName);
        $synchronizationDataTransfer->setReference($blockKey);

        return $this->synchronizationService->getStorageKeyBuilder($resourceName)->generateKey($synchronizationDataTransfer);
    }

    /**
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\CmsBlockRequestTransfer
     */
    protected function mapOptionsToTransfer(array $options): CmsBlockRequestTransfer
    {
        return (new CmsBlockRequestTransfer())->fromArray($options, true);
    }
}
