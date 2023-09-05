<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointStorage\Reader;

use Generated\Shared\Transfer\ServiceTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ServiceTypeStorageConditionsTransfer;
use Generated\Shared\Transfer\ServiceTypeStorageCriteriaTransfer;
use Generated\Shared\Transfer\ServiceTypeStorageTransfer;
use Spryker\Client\ServicePointStorage\Dependency\Client\ServicePointStorageToStorageClientInterface;
use Spryker\Client\ServicePointStorage\Dependency\Service\ServicePointStorageToUtilEncodingServiceInterface;
use Spryker\Client\ServicePointStorage\Generator\StorageKeyGeneratorInterface;
use Spryker\Client\ServicePointStorage\Mapper\ServicePointStorageMapperInterface;
use Spryker\Shared\ServicePointStorage\ServicePointStorageConfig;

class ServiceTypeStorageReader implements ServiceTypeStorageReaderInterface
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
     * @var \Spryker\Client\ServicePointStorage\Generator\StorageKeyGeneratorInterface
     */
    protected StorageKeyGeneratorInterface $storageKeyGenerator;

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
     * @param \Spryker\Client\ServicePointStorage\Generator\StorageKeyGeneratorInterface $storageKeyGenerator
     * @param \Spryker\Client\ServicePointStorage\Dependency\Service\ServicePointStorageToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Client\ServicePointStorage\Mapper\ServicePointStorageMapperInterface $servicePointStorageMapper
     */
    public function __construct(
        ServicePointStorageToStorageClientInterface $storageClient,
        StorageKeyGeneratorInterface $storageKeyGenerator,
        ServicePointStorageToUtilEncodingServiceInterface $utilEncodingService,
        ServicePointStorageMapperInterface $servicePointStorageMapper
    ) {
        $this->storageClient = $storageClient;
        $this->storageKeyGenerator = $storageKeyGenerator;
        $this->utilEncodingService = $utilEncodingService;
        $this->servicePointStorageMapper = $servicePointStorageMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeStorageCriteriaTransfer $serviceTypeStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeStorageCollectionTransfer
     */
    public function getServiceTypeStorageCollection(
        ServiceTypeStorageCriteriaTransfer $serviceTypeStorageCriteriaTransfer
    ): ServiceTypeStorageCollectionTransfer {
        $serviceTypeStorageConditionsTransfer = $serviceTypeStorageCriteriaTransfer->getServiceTypeStorageConditionsOrFail();

        if ($serviceTypeStorageConditionsTransfer->getServiceTypeIds() !== []) {
            return $this->getServiceTypeStorageCollectionByServiceTypeIds($serviceTypeStorageConditionsTransfer);
        }

        if ($serviceTypeStorageConditionsTransfer->getUuids() !== []) {
            return $this->getServiceTypeStorageCollectionByUuids($serviceTypeStorageConditionsTransfer);
        }

        return new ServiceTypeStorageCollectionTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeStorageConditionsTransfer $serviceTypeStorageConditionsTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeStorageCollectionTransfer
     */
    protected function getServiceTypeStorageCollectionByServiceTypeIds(
        ServiceTypeStorageConditionsTransfer $serviceTypeStorageConditionsTransfer
    ): ServiceTypeStorageCollectionTransfer {
        $serviceTypeStorageCollectionTransfer = new ServiceTypeStorageCollectionTransfer();

        $storageKeys = $this->storageKeyGenerator->generateIdKeys(
            $serviceTypeStorageConditionsTransfer->getServiceTypeIds(),
            ServicePointStorageConfig::SERVICE_TYPE_RESOURCE_NAME,
        );

        $serviceTypeStorageData = array_filter($this->storageClient->getMulti($storageKeys));
        if (!$serviceTypeStorageData) {
            return $serviceTypeStorageCollectionTransfer;
        }

        foreach ($serviceTypeStorageData as $serviceTypeStorageItem) {
            $decodedServiceTypeStorageItem = $this->utilEncodingService->decodeJson($serviceTypeStorageItem, true);
            if (!$decodedServiceTypeStorageItem) {
                continue;
            }

            $serviceTypeStorageTransfer = $this->servicePointStorageMapper->mapServiceTypeStorageDataToServiceTypeStorageTransfer(
                $decodedServiceTypeStorageItem,
                new ServiceTypeStorageTransfer(),
            );

            $serviceTypeStorageCollectionTransfer->addServiceTypeStorage($serviceTypeStorageTransfer);
        }

        return $serviceTypeStorageCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeStorageConditionsTransfer $serviceTypeStorageConditionsTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeStorageCollectionTransfer
     */
    protected function getServiceTypeStorageCollectionByUuids(
        ServiceTypeStorageConditionsTransfer $serviceTypeStorageConditionsTransfer
    ): ServiceTypeStorageCollectionTransfer {
        $serviceTypeStorageCollectionTransfer = new ServiceTypeStorageCollectionTransfer();

        $storageKeys = $this->storageKeyGenerator->generateUuidKeys(
            $serviceTypeStorageConditionsTransfer->getUuids(),
            ServicePointStorageConfig::SERVICE_TYPE_RESOURCE_NAME,
        );

        $serviceTypeStorageData = array_filter($this->storageClient->getMulti($storageKeys));
        if (!$serviceTypeStorageData) {
            return $serviceTypeStorageCollectionTransfer;
        }

        $serviceTypeIds = [];
        foreach ($serviceTypeStorageData as $serviceTypeStorageDataItem) {
            $decodedStorageDataItem = $this->utilEncodingService->decodeJson($serviceTypeStorageDataItem, true);
            if (!isset($decodedStorageDataItem[static::KEY_ID])) {
                continue;
            }

            $serviceTypeIds[] = (int)$decodedStorageDataItem[static::KEY_ID];
        }

        $serviceTypeStorageConditionsTransfer->setServiceTypeIds($serviceTypeIds);

        return $this->getServiceTypeStorageCollectionByServiceTypeIds($serviceTypeStorageConditionsTransfer);
    }
}
