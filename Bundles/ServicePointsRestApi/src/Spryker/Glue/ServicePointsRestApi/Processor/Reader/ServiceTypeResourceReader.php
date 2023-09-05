<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi\Processor\Reader;

use Generated\Shared\Transfer\ServiceTypeResourceCollectionTransfer;
use Generated\Shared\Transfer\ServiceTypeResourceCriteriaTransfer;
use Generated\Shared\Transfer\ServiceTypeStorageConditionsTransfer;
use Generated\Shared\Transfer\ServiceTypeStorageCriteriaTransfer;
use Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToServicePointStorageClientInterface;
use Spryker\Glue\ServicePointsRestApi\Processor\Mapper\ServiceTypeMapperInterface;

class ServiceTypeResourceReader implements ServiceTypeResourceReaderInterface
{
    /**
     * @var \Spryker\Glue\ServicePointsRestApi\Processor\Mapper\ServiceTypeMapperInterface
     */
    protected ServiceTypeMapperInterface $serviceTypeMapper;

    /**
     * @var \Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToServicePointStorageClientInterface
     */
    protected ServicePointsRestApiToServicePointStorageClientInterface $servicePointStorageClient;

    /**
     * @param \Spryker\Glue\ServicePointsRestApi\Processor\Mapper\ServiceTypeMapperInterface $serviceTypeMapper
     * @param \Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToServicePointStorageClientInterface $servicePointStorageClient
     */
    public function __construct(
        ServiceTypeMapperInterface $serviceTypeMapper,
        ServicePointsRestApiToServicePointStorageClientInterface $servicePointStorageClient
    ) {
        $this->serviceTypeMapper = $serviceTypeMapper;
        $this->servicePointStorageClient = $servicePointStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeResourceCriteriaTransfer $serviceTypeResourceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeResourceCollectionTransfer
     */
    public function getServiceTypeResourceCollection(
        ServiceTypeResourceCriteriaTransfer $serviceTypeResourceCriteriaTransfer
    ): ServiceTypeResourceCollectionTransfer {
        $serviceTypeStorageCriteriaTransfer = $this->createServiceTypeStorageCriteriaTransfer($serviceTypeResourceCriteriaTransfer);
        $serviceTypeStorageCollectionTransfer = $this->servicePointStorageClient
            ->getServiceTypeStorageCollection($serviceTypeStorageCriteriaTransfer);

        return $this->serviceTypeMapper->mapServiceTypeStorageCollectionToServiceTypeResourceCollection(
            $serviceTypeStorageCollectionTransfer,
            new ServiceTypeResourceCollectionTransfer(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeResourceCriteriaTransfer $serviceTypeResourceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeStorageCriteriaTransfer
     */
    protected function createServiceTypeStorageCriteriaTransfer(
        ServiceTypeResourceCriteriaTransfer $serviceTypeResourceCriteriaTransfer
    ): ServiceTypeStorageCriteriaTransfer {
        $serviceTypeStorageCriteriaTransfer = new ServiceTypeStorageCriteriaTransfer();
        if (
            !$serviceTypeResourceCriteriaTransfer->getServiceTypeResourceConditions()
            || $serviceTypeResourceCriteriaTransfer->getServiceTypeResourceConditionsOrFail()->getUuids() === []
        ) {
            return $serviceTypeStorageCriteriaTransfer->setServiceTypeStorageConditions(new ServiceTypeStorageConditionsTransfer());
        }

        $serviceTypeStorageConditionsTransfer = (new ServiceTypeStorageConditionsTransfer())
            ->fromArray($serviceTypeResourceCriteriaTransfer->getServiceTypeResourceConditionsOrFail()->toArray(), true);

        return $serviceTypeStorageCriteriaTransfer->setServiceTypeStorageConditions($serviceTypeStorageConditionsTransfer);
    }
}
