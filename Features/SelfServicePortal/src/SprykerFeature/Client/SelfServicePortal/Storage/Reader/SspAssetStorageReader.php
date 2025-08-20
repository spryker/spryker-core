<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Storage\Reader;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SspAssetStorageCollectionTransfer;
use Generated\Shared\Transfer\SspAssetStorageCriteriaTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use SprykerFeature\Client\SelfServicePortal\Permission\SspAssetPermissionCheckerInterface;
use SprykerFeature\Client\SelfServicePortal\Storage\Mapper\SspAssetStorageMapperInterface;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig;

class SspAssetStorageReader implements SspAssetStorageReaderInterface
{
    public function __construct(
        protected StorageClientInterface $storageClient,
        protected SynchronizationServiceInterface $synchronizationService,
        protected UtilEncodingServiceInterface $utilEncodingService,
        protected SspAssetStorageMapperInterface $sspAssetStorageMapper,
        protected SspAssetPermissionCheckerInterface $sspAssetPermissionChecker
    ) {
    }

    public function getSspAssetStorageCollection(
        SspAssetStorageCriteriaTransfer $sspAssetStorageCriteriaTransfer
    ): SspAssetStorageCollectionTransfer {
        $sspAssetStorageCriteriaTransfer->requireSspAssetStorageConditions()
            ->requireCompanyUser();

        $references = $sspAssetStorageCriteriaTransfer->getSspAssetStorageConditionsOrFail()->getReferences();

        if (!$references) {
            return new SspAssetStorageCollectionTransfer();
        }

        return $this->getSspAssetStorageCollectionByReferences($references, $sspAssetStorageCriteriaTransfer->getCompanyUserOrFail());
    }

    /**
     * @param list<string> $references
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUser
     *
     * @return \Generated\Shared\Transfer\SspAssetStorageCollectionTransfer
     */
    protected function getSspAssetStorageCollectionByReferences(array $references, CompanyUserTransfer $companyUser): SspAssetStorageCollectionTransfer
    {
        $sspAssetStorageCollectionTransfer = new SspAssetStorageCollectionTransfer();
        $storageKeys = $this->generateSspAssetStorageKeys($references);
        if ($storageKeys === []) {
            return $sspAssetStorageCollectionTransfer;
        }

        $storageDataCollection = $this->storageClient->getMulti($storageKeys);

        foreach ($storageDataCollection as $storageData) {
            $decodedStorageData = $this->utilEncodingService->decodeJson($storageData, true);
            if (!is_array($decodedStorageData) || !$this->sspAssetPermissionChecker->canViewSspAsset($decodedStorageData, $companyUser)) {
                continue;
            }

            $sspAssetStorageCollectionTransfer->addSspAssetStorage(
                $this->sspAssetStorageMapper->mapStorageDataToSspAssetStorageTransfer($decodedStorageData),
            );
        }

        return $sspAssetStorageCollectionTransfer;
    }

    /**
     * @param list<string> $references
     *
     * @return list<string>
     */
    protected function generateSspAssetStorageKeys(array $references): array
    {
        $storageKeys = [];
        foreach ($references as $reference) {
            $storageKeys[] = $this->generateStorageKeyByReference($reference);
        }

        return $storageKeys;
    }

    protected function generateStorageKeyByReference(string $reference): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference($reference);

        return $this->synchronizationService->getStorageKeyBuilder(SelfServicePortalConfig::SSP_ASSET_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
