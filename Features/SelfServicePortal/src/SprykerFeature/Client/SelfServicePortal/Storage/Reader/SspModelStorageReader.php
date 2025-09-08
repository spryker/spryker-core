<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Storage\Reader;

use Generated\Shared\Transfer\SspModelStorageCollectionTransfer;
use Generated\Shared\Transfer\SspModelStorageConditionsTransfer;
use Generated\Shared\Transfer\SspModelStorageCriteriaTransfer;
use Generated\Shared\Transfer\SspModelStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use SprykerFeature\Client\SelfServicePortal\Storage\Mapper\SspModelStorageMapperInterface;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig;

class SspModelStorageReader implements SspModelStorageReaderInterface
{
    public function __construct(
        protected StorageClientInterface $storageClient,
        protected SynchronizationServiceInterface $synchronizationService,
        protected UtilEncodingServiceInterface $utilEncodingService,
        protected SspModelStorageMapperInterface $sspModelStorageMapper
    ) {
    }

    public function getSspModelStorageCollection(
        SspModelStorageCriteriaTransfer $sspModelStorageCriteriaTransfer
    ): SspModelStorageCollectionTransfer {
        $sspModelStorageConditionsTransfer = $sspModelStorageCriteriaTransfer->getSspModelStorageConditions();
        if ($sspModelStorageConditionsTransfer === null) {
            return new SspModelStorageCollectionTransfer();
        }

        $sspModelIds = $sspModelStorageConditionsTransfer->getSspModelIds();
        if (!$sspModelIds) {
            return new SspModelStorageCollectionTransfer();
        }

        return $this->getSspModelStorageByModelIds($sspModelIds);
    }

     /**
      * @param list<int> $modelIds
      *
      * @return list<\Generated\Shared\Transfer\SspModelStorageTransfer>
      */
    public function getSspModelStoragesByIds(array $modelIds): array
    {
        $sspModelStorageCriteriaTransfer = (new SspModelStorageCriteriaTransfer())
            ->setSspModelStorageConditions(
                (new SspModelStorageConditionsTransfer())
                    ->setSspModelIds($modelIds),
            );

        $sspModelStorageCollectionTransfer = $this->getSspModelStorageCollection($sspModelStorageCriteriaTransfer);

        if ($sspModelStorageCollectionTransfer->getSspModelStorages()->count() === 0) {
            return [];
        }

        return $sspModelStorageCollectionTransfer->getSspModelStorages()->getArrayCopy();
    }

    /**
     * @param list<int> $sspModelIds
     *
     * @return \Generated\Shared\Transfer\SspModelStorageCollectionTransfer
     */
    protected function getSspModelStorageByModelIds(array $sspModelIds): SspModelStorageCollectionTransfer
    {
        $sspModelStorageCollectionTransfer = new SspModelStorageCollectionTransfer();
        $storageKeys = $this->generateSspModelStorageKeys($sspModelIds);

        if ($storageKeys === []) {
            return $sspModelStorageCollectionTransfer;
        }

        $sspModelStorageData = $this->storageClient->getMulti($storageKeys);

        foreach ($sspModelStorageData as $sspModelStorageDataItem) {
            $sspModelStorageTransfer = $this->processStorageDataItem($sspModelStorageDataItem);
            if ($sspModelStorageTransfer !== null) {
                $sspModelStorageCollectionTransfer->addSspModelStorage($sspModelStorageTransfer);
            }
        }

        return $sspModelStorageCollectionTransfer;
    }

    protected function processStorageDataItem(?string $sspModelStorageDataItem): ?SspModelStorageTransfer
    {
        if (!$sspModelStorageDataItem) {
            return null;
        }

        $decodedStorageData = $this->utilEncodingService->decodeJson($sspModelStorageDataItem, true);
        if (!is_array($decodedStorageData)) {
            return null;
        }

        return $this->sspModelStorageMapper->mapStorageDataToSspModelStorageTransfer($decodedStorageData);
    }

    /**
     * @param list<int> $sspModelIds
     *
     * @return list<string>
     */
    protected function generateSspModelStorageKeys(array $sspModelIds): array
    {
        $storageKeys = [];
        foreach ($sspModelIds as $sspModelId) {
            $synchronizationDataTransfer = (new SynchronizationDataTransfer())
                ->setReference((string)$sspModelId);

            $storageKeys[] = $this->synchronizationService
                ->getStorageKeyBuilder(SelfServicePortalConfig::SSP_MODEL_RESOURCE_NAME)
                ->generateKey($synchronizationDataTransfer);
        }

        return $storageKeys;
    }
}
