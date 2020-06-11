<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsStorage\Reader;

use Generated\Shared\Transfer\CmsPageStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\CmsStorage\Dependency\Client\CmsStorageToStorageClientInterface;
use Spryker\Client\CmsStorage\Dependency\Service\CmsStorageToSynchronizationServiceInterface;
use Spryker\Client\CmsStorage\Dependency\Service\CmsStorageToUtilEncodingServiceInterface;
use Spryker\Shared\CmsStorage\CmsStorageConstants;

class CmsPageStorageReader implements CmsPageStorageReaderInterface
{
    protected const KEY_ID = 'id';
    protected const KEY_UUID = 'uuid';

    /**
     * @var \Spryker\Client\CmsStorage\Dependency\Client\CmsStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\CmsStorage\Dependency\Service\CmsStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\CmsStorage\Dependency\Service\CmsStorageToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Client\CmsStorage\Dependency\Client\CmsStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\CmsStorage\Dependency\Service\CmsStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\CmsStorage\Dependency\Service\CmsStorageToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        CmsStorageToStorageClientInterface $storageClient,
        CmsStorageToSynchronizationServiceInterface $synchronizationService,
        CmsStorageToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @phpstan-return array<string, \Generated\Shared\Transfer\CmsPageStorageTransfer>
     *
     * @param string[] $cmsPageUuids
     * @param string $mappingType
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CmsPageStorageTransfer[]
     */
    public function getCmsPagesByUuids(array $cmsPageUuids, string $mappingType, string $localeName, string $storeName): array
    {
        $cmsPagesStorageMappingData = $this->storageClient->getMulti(
            $this->generateKeys($cmsPageUuids, $localeName, $storeName, $mappingType)
        );

        if (!$cmsPagesStorageMappingData) {
            return [];
        }

        $cmsPageIds = [];
        foreach ($cmsPagesStorageMappingData as $mappingDatum) {
            if (!$mappingDatum) {
                continue;
            }

            $cmsPagesStorageMappingData = $this->utilEncodingService->decodeJson($mappingDatum, true);
            if (!is_array($cmsPagesStorageMappingData)) {
                continue;
            }
            $cmsPageIds[] = $cmsPagesStorageMappingData[static::KEY_ID];
        }

        if (!$cmsPageIds) {
            return [];
        }

        return $this->getCmsPagesByIds($cmsPageIds, $localeName, $storeName);
    }

    /**
     * @phpstan-return array<string, \Generated\Shared\Transfer\CmsPageStorageTransfer>
     *
     * @param string[] $cmsPageIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CmsPageStorageTransfer[]
     */
    protected function getCmsPagesByIds(array $cmsPageIds, string $localeName, string $storeName): array
    {
        $cmsPagesStorageData = $this->storageClient->getMulti(
            $this->generateKeys($cmsPageIds, $localeName, $storeName)
        );

        if (!$cmsPagesStorageData) {
            return [];
        }

        $cmsPagesStorageTransfers = [];
        foreach ($cmsPagesStorageData as $cmsPagesStorageDatum) {
            if (!$cmsPagesStorageDatum) {
                continue;
            }

            $cmsPagesStorageData = $this->utilEncodingService->decodeJson($cmsPagesStorageDatum, true);
            if (!is_array($cmsPagesStorageData)) {
                continue;
            }

            $cmsPagesStorageTransfers[$cmsPagesStorageData[static::KEY_UUID]] = (new CmsPageStorageTransfer())
                ->fromArray($cmsPagesStorageData, true);
        }

        return $cmsPagesStorageTransfers;
    }

    /**
     * @param string[] $cmsPageUuids
     * @param string $localeName
     * @param string $storeName
     * @param string|null $mappingType
     *
     * @return string[]
     */
    protected function generateKeys(array $cmsPageUuids, string $localeName, string $storeName, ?string $mappingType = null): array
    {
        $cmsPageStorageKeys = [];
        foreach ($cmsPageUuids as $cmsPageUuid) {
            $cmsPageReference = $mappingType ? $mappingType . ':' . $cmsPageUuid : $cmsPageUuid;
            $cmsPageStorageKeys[] = $this->generateKey($cmsPageReference, $localeName, $storeName);
        }

        return $cmsPageStorageKeys;
    }

    /**
     * @param string $cmsPageReference
     * @param string $localeName
     * @param string $storeName
     *
     * @return string
     */
    protected function generateKey(string $cmsPageReference, string $localeName, string $storeName): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference($cmsPageReference)
            ->setLocale($localeName)
            ->setStore($storeName);

        return $this->synchronizationService
            ->getStorageKeyBuilder(CmsStorageConstants::CMS_PAGE_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
