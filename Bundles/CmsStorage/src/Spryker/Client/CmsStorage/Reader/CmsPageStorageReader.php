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
     * @param string[] $cmsPageUuids
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CmsPageStorageTransfer[]
     */
    public function getCmsPagesByUuids(array $cmsPageUuids, string $localeName, string $storeName): array
    {
        $cmsPagesStorageMappingData = $this->storageClient->getMulti(
            $this->generateUuidKeys($cmsPageUuids, $localeName, $storeName)
        );

        if (!$cmsPagesStorageMappingData) {
            return [];
        }

        $cmsPageIds = [];
        foreach ($cmsPagesStorageMappingData as $mappingDatum) {
            if (!$mappingDatum) {
                continue;
            }

            $decodedCmsPageStorageMappingData = $this->utilEncodingService->decodeJson($mappingDatum, true);
            if (!is_array($decodedCmsPageStorageMappingData)) {
                continue;
            }
            $cmsPageIds[] = $decodedCmsPageStorageMappingData['id'];
        }

        if (!$cmsPageIds) {
            return [];
        }

        return $this->getCmsPagesByIds($cmsPageIds, $localeName, $storeName);
    }

    /**
     * @param int[] $cmsPageIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CmsPageStorageTransfer[]
     */
    public function getCmsPagesByIds(array $cmsPageIds, string $localeName, string $storeName): array
    {
        $cmsPagesStorageData = $this->storageClient->getMulti(
            $this->generateIdKeys($cmsPageIds, $localeName, $storeName)
        );

        if (!$cmsPagesStorageData) {
            return [];
        }

        $cmsPagesStorageTransfers = [];
        foreach ($cmsPagesStorageData as $cmsPageStorageDatum) {
            if (!$cmsPageStorageDatum) {
                continue;
            }

            $decodedCmsPageStorageData = $this->utilEncodingService->decodeJson($cmsPageStorageDatum, true);
            if (!is_array($decodedCmsPageStorageData)) {
                continue;
            }

            $cmsPagesStorageTransfers[] = (new CmsPageStorageTransfer())
                ->fromArray($decodedCmsPageStorageData, true);
        }

        return $cmsPagesStorageTransfers;
    }

    /**
     * @param string[] $cmsPageUuids
     * @param string $localeName
     * @param string $storeName
     *
     * @return string[]
     */
    protected function generateUuidKeys(array $cmsPageUuids, string $localeName, string $storeName): array
    {
        $cmsPageStorageKeys = [];
        foreach ($cmsPageUuids as $cmsPageUuid) {
            $cmsPageReference = sprintf('%s:%s', 'uuid', $cmsPageUuid);
            $cmsPageStorageKeys[] = $this->generateKey($cmsPageReference, $localeName, $storeName);
        }

        return $cmsPageStorageKeys;
    }

    /**
     * @param int[] $cmsPageIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return string[]
     */
    protected function generateIdKeys(array $cmsPageIds, string $localeName, string $storeName): array
    {
        $cmsPageStorageKeys = [];
        foreach ($cmsPageIds as $cmsPageId) {
            $cmsPageStorageKeys[] = $this->generateKey((string)$cmsPageId, $localeName, $storeName);
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
