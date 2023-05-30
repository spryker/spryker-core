<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Reader;

use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\ServiceCollectionTransfer;
use Generated\Shared\Transfer\ServiceConditionsTransfer;
use Generated\Shared\Transfer\ServiceCriteriaTransfer;
use Generated\Shared\Transfer\ServiceTypeConditionsTransfer;
use Generated\Shared\Transfer\ServiceTypeCriteriaTransfer;
use Generated\Shared\Transfer\ServiceTypeResourceCollectionTransfer;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServiceTypeMapperInterface;

class ServiceTypeByServiceResourceRelationshipReader implements ServiceTypeByServiceResourceRelationshipReaderInterface
{
    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface
     */
    protected ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServiceTypeMapperInterface
     */
    protected ServiceTypeMapperInterface $serviceTypeMapper;

    /**
     * @param \Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServiceTypeMapperInterface $serviceTypeMapper
     */
    public function __construct(
        ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade,
        ServiceTypeMapperInterface $serviceTypeMapper
    ) {
        $this->servicePointFacade = $servicePointFacade;
        $this->serviceTypeMapper = $serviceTypeMapper;
    }

    /**
     * @param list<string> $serviceUuids
     *
     * @return array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer>
     */
    public function getServiceTypeRelationshipsIndexedByServiceUuids(array $serviceUuids): array
    {
        $serviceCollectionTransfer = $this->getServiceCollectionByServiceUuids($serviceUuids);
        $serviceTypeUuidsIndexedByServiceUuids = $this->getServiceTypeUuidsIndexedByServiceUuids($serviceCollectionTransfer);
        $serviceTypeResourcesIndexedByServiceTypeUuid = $this
            ->getServiceTypeResourcesIndexedByServiceTypeUuid($serviceTypeUuidsIndexedByServiceUuids);

        $serviceTypeRelationshipTransfersIndexedByServiceUuid = [];
        foreach ($serviceTypeUuidsIndexedByServiceUuids as $serviceUuid => $serviceTypeUuid) {
            $serviceTypeResource = $serviceTypeResourcesIndexedByServiceTypeUuid[$serviceTypeUuid] ?? null;
            if ($serviceTypeResource === null) {
                continue;
            }

            $serviceTypeRelationshipTransfersIndexedByServiceUuid[$serviceUuid] = (new GlueRelationshipTransfer())->addResource($serviceTypeResource);
        }

        return $serviceTypeRelationshipTransfersIndexedByServiceUuid;
    }

    /**
     * @param list<string> $serviceUuids
     *
     * @return \Generated\Shared\Transfer\ServiceCollectionTransfer
     */
    protected function getServiceCollectionByServiceUuids(array $serviceUuids): ServiceCollectionTransfer
    {
        $serviceConditionsTransfer = (new ServiceConditionsTransfer())->setUuids($serviceUuids);

        return $this->servicePointFacade->getServiceCollection(
            (new ServiceCriteriaTransfer())->setServiceConditions($serviceConditionsTransfer),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceCollectionTransfer $serviceCollectionTransfer
     *
     * @return array<string, string>
     */
    protected function getServiceTypeUuidsIndexedByServiceUuids(ServiceCollectionTransfer $serviceCollectionTransfer): array
    {
        $indexedServiceTypeUuids = [];
        foreach ($serviceCollectionTransfer->getServices() as $serviceTransfer) {
            $indexedServiceTypeUuids[$serviceTransfer->getUuidOrFail()] = $serviceTransfer->getServiceTypeOrFail()->getUuidOrFail();
        }

        return $indexedServiceTypeUuids;
    }

    /**
     * @param array<string, string> $serviceTypeUuidsIndexedByServiceUuids
     *
     * @return array<string, \Generated\Shared\Transfer\GlueResourceTransfer>
     */
    protected function getServiceTypeResourcesIndexedByServiceTypeUuid(array $serviceTypeUuidsIndexedByServiceUuids): array
    {
        $serviceTypeCriteriaTransfer = (new ServiceTypeCriteriaTransfer())
            ->setServiceTypeConditions((new ServiceTypeConditionsTransfer())
                ->setUuids($serviceTypeUuidsIndexedByServiceUuids));

        $serviceTypeCollection = $this->servicePointFacade->getServiceTypeCollection($serviceTypeCriteriaTransfer);

        $serviceTypeResourceCollectionTransfer = $this->serviceTypeMapper->mapServiceTypeTransfersToServiceTypeResourceCollectionTransfer(
            $serviceTypeCollection->getServiceTypes(),
            new ServiceTypeResourceCollectionTransfer(),
        );

        $serviceTypeResourcesIndexedByServiceTypeUuid = [];
        foreach ($serviceTypeResourceCollectionTransfer->getServiceTypeResources() as $serviceTypeResourceTransfer) {
            $serviceTypeResourcesIndexedByServiceTypeUuid[$serviceTypeResourceTransfer->getIdOrFail()] = $serviceTypeResourceTransfer;
        }

        return $serviceTypeResourcesIndexedByServiceTypeUuid;
    }
}
