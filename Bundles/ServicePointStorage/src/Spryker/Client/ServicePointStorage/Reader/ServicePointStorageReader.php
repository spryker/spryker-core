<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointStorage\Reader;

use Generated\Shared\Transfer\ServicePointStorageCollectionTransfer;
use Generated\Shared\Transfer\ServicePointStorageConditionsTransfer;
use Generated\Shared\Transfer\ServicePointStorageCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointStorageTransfer;
use Spryker\Client\ServicePointStorage\Dependency\Client\ServicePointStorageToStorageClientInterface;
use Spryker\Client\ServicePointStorage\Dependency\Service\ServicePointStorageToUtilEncodingServiceInterface;
use Spryker\Client\ServicePointStorage\Generator\ServicePointStorageKeyGeneratorInterface;
use Spryker\Client\ServicePointStorage\Mapper\ServicePointStorageMapperInterface;

class ServicePointStorageReader implements ServicePointStorageReaderInterface
{
    /**
     * @var string
     */
    protected const KEY_ID = 'id';

    /**
     * @var \Spryker\Client\ServicePointStorage\Dependency\Client\ServicePointStorageToStorageClientInterface
     */
    protected ServicePointStorageToStorageClientInterface $storageClient;

    /**
     * @var \Spryker\Client\ServicePointStorage\Generator\ServicePointStorageKeyGeneratorInterface
     */
    protected ServicePointStorageKeyGeneratorInterface $servicePointStorageKeyGenerator;

    /**
     * @var \Spryker\Client\ServicePointStorage\Dependency\Service\ServicePointStorageToUtilEncodingServiceInterface
     */
    protected ServicePointStorageToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @var \Spryker\Client\ServicePointStorage\Mapper\ServicePointStorageMapperInterface
     */
    protected ServicePointStorageMapperInterface $servicePointStorageMapper;

    /**
     * @param \Spryker\Client\ServicePointStorage\Dependency\Client\ServicePointStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ServicePointStorage\Generator\ServicePointStorageKeyGeneratorInterface $servicePointStorageKeyGenerator
     * @param \Spryker\Client\ServicePointStorage\Dependency\Service\ServicePointStorageToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Client\ServicePointStorage\Mapper\ServicePointStorageMapperInterface $servicePointStorageMapper
     */
    public function __construct(
        ServicePointStorageToStorageClientInterface $storageClient,
        ServicePointStorageKeyGeneratorInterface $servicePointStorageKeyGenerator,
        ServicePointStorageToUtilEncodingServiceInterface $utilEncodingService,
        ServicePointStorageMapperInterface $servicePointStorageMapper
    ) {
        $this->storageClient = $storageClient;
        $this->servicePointStorageKeyGenerator = $servicePointStorageKeyGenerator;
        $this->utilEncodingService = $utilEncodingService;
        $this->servicePointStorageMapper = $servicePointStorageMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointStorageCriteriaTransfer $servicePointStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointStorageCollectionTransfer
     */
    public function getServicePointStorageCollection(
        ServicePointStorageCriteriaTransfer $servicePointStorageCriteriaTransfer
    ): ServicePointStorageCollectionTransfer {
        $servicePointStorageConditionsTransfer = $servicePointStorageCriteriaTransfer->getServicePointStorageConditionsOrFail();
        if (count($servicePointStorageConditionsTransfer->getServicePointIds())) {
            return $this->getServicePointStorageCollectionByServicePointIds($servicePointStorageConditionsTransfer);
        }

        if (count($servicePointStorageConditionsTransfer->getUuids())) {
            return $this->getServicePointStorageCollectionByUuids($servicePointStorageConditionsTransfer);
        }

        return new ServicePointStorageCollectionTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointStorageConditionsTransfer $servicePointStorageConditionsTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointStorageCollectionTransfer
     */
    protected function getServicePointStorageCollectionByServicePointIds(
        ServicePointStorageConditionsTransfer $servicePointStorageConditionsTransfer
    ): ServicePointStorageCollectionTransfer {
        $servicePointStorageCollectionTransfer = new ServicePointStorageCollectionTransfer();

        $storageKeys = $this->servicePointStorageKeyGenerator->generateServicePointIdKeys(
            $servicePointStorageConditionsTransfer->getServicePointIds(),
            $servicePointStorageConditionsTransfer->getStoreNameOrFail(),
        );
        if (!$storageKeys) {
            return $servicePointStorageCollectionTransfer;
        }

        $servicePointStorageData = array_filter($this->storageClient->getMulti($storageKeys));
        if (!$servicePointStorageData) {
            return $servicePointStorageCollectionTransfer;
        }

        foreach ($servicePointStorageData as $servicePointStorageItem) {
            $decodedServicePointStorageItem = $this->utilEncodingService->decodeJson($servicePointStorageItem, true);
            if (!$decodedServicePointStorageItem) {
                continue;
            }

            $servicePointStorageTransfer = $this->servicePointStorageMapper->mapServicePointStorageDataToServicePointStorageTransfer(
                $decodedServicePointStorageItem,
                new ServicePointStorageTransfer(),
            );

            $servicePointStorageCollectionTransfer->addServicePointStorage($servicePointStorageTransfer);
        }

        return $servicePointStorageCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointStorageConditionsTransfer $servicePointStorageConditionsTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointStorageCollectionTransfer
     */
    protected function getServicePointStorageCollectionByUuids(
        ServicePointStorageConditionsTransfer $servicePointStorageConditionsTransfer
    ): ServicePointStorageCollectionTransfer {
        $servicePointStorageCollectionTransfer = new ServicePointStorageCollectionTransfer();

        $storageKeys = $this->servicePointStorageKeyGenerator->generateUuidKeys(
            $servicePointStorageConditionsTransfer->getUuids(),
            $servicePointStorageConditionsTransfer->getStoreNameOrFail(),
        );

        if (!$storageKeys) {
            return $servicePointStorageCollectionTransfer;
        }

        $storageData = array_filter($this->storageClient->getMulti($storageKeys));
        if (!$storageData) {
            return $servicePointStorageCollectionTransfer;
        }

        $servicePointIds = [];
        foreach ($storageData as $storageDataItem) {
            $decodedStorageDataItem = $this->utilEncodingService->decodeJson($storageDataItem, true);
            if (!$decodedStorageDataItem || !isset($decodedStorageDataItem[static::KEY_ID])) {
                continue;
            }

            $servicePointIds[] = (int)$decodedStorageDataItem[static::KEY_ID];
        }

        $servicePointStorageConditionsTransfer->setServicePointIds($servicePointIds);

        return $this->getServicePointStorageCollectionByServicePointIds($servicePointStorageConditionsTransfer);
    }
}
