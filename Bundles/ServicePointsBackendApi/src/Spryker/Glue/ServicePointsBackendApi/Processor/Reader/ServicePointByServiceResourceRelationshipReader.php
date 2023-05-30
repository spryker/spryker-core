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
use Generated\Shared\Transfer\ServicePointConditionsTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointResourceCollectionTransfer;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointMapperInterface;

class ServicePointByServiceResourceRelationshipReader implements ServicePointByServiceResourceRelationshipReaderInterface
{
    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface
     */
    protected ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointMapperInterface
     */
    protected ServicePointMapperInterface $servicePointMapper;

    /**
     * @param \Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointMapperInterface $servicePointMapper
     */
    public function __construct(
        ServicePointsBackendApiToServicePointFacadeInterface $servicePointFacade,
        ServicePointMapperInterface $servicePointMapper
    ) {
        $this->servicePointFacade = $servicePointFacade;
        $this->servicePointMapper = $servicePointMapper;
    }

    /**
     * @param list<string> $serviceUuids
     *
     * @return array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer>
     */
    public function getServicePointRelationshipsIndexedByServiceUuids(array $serviceUuids): array
    {
        $serviceCollectionTransfer = $this->getServiceCollectionByServiceUuids($serviceUuids);
        $servicePointUuidsIndexedByServiceUuids = $this->getServicePointUuidsIndexedByServiceUuids($serviceCollectionTransfer);
        $servicePointResourcesIndexedByServicePointUuid = $this
            ->getServicePointResourcesIndexedByServicePointUuid($servicePointUuidsIndexedByServiceUuids);

        $servicePointRelationshipTransfersIndexedByServiceUuid = [];
        foreach ($servicePointUuidsIndexedByServiceUuids as $serviceUuid => $servicePointUuid) {
            $servicePointResource = $servicePointResourcesIndexedByServicePointUuid[$servicePointUuid] ?? null;
            if ($servicePointResource === null) {
                continue;
            }

            $servicePointRelationshipTransfersIndexedByServiceUuid[$serviceUuid] = (new GlueRelationshipTransfer())->addResource($servicePointResource);
        }

        return $servicePointRelationshipTransfersIndexedByServiceUuid;
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
    protected function getServicePointUuidsIndexedByServiceUuids(ServiceCollectionTransfer $serviceCollectionTransfer): array
    {
        $indexedServicePointUuids = [];
        foreach ($serviceCollectionTransfer->getServices() as $serviceTransfer) {
            $indexedServicePointUuids[$serviceTransfer->getUuidOrFail()] = $serviceTransfer->getServicePointOrFail()->getUuidOrFail();
        }

        return $indexedServicePointUuids;
    }

    /**
     * @param array<string, string> $servicePointUuidsIndexedByServiceUuids
     *
     * @return array<string, \Generated\Shared\Transfer\GlueResourceTransfer>
     */
    protected function getServicePointResourcesIndexedByServicePointUuid(array $servicePointUuidsIndexedByServiceUuids): array
    {
        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->setServicePointConditions((new ServicePointConditionsTransfer())
                ->setUuids($servicePointUuidsIndexedByServiceUuids)
                ->setWithStoreRelations(true));

        $servicePointCollection = $this->servicePointFacade->getServicePointCollection($servicePointCriteriaTransfer);

        $servicePointResourceCollectionTransfer = $this->servicePointMapper->mapServicePointTransfersToServicePointResourceCollectionTransfer(
            $servicePointCollection->getServicePoints(),
            new ServicePointResourceCollectionTransfer(),
        );

        $servicePointResourcesIndexedByServicePointUuid = [];
        foreach ($servicePointResourceCollectionTransfer->getServicePointResources() as $servicePointResourceTransfer) {
            $servicePointResourcesIndexedByServicePointUuid[$servicePointResourceTransfer->getIdOrFail()] = $servicePointResourceTransfer;
        }

        return $servicePointResourcesIndexedByServicePointUuid;
    }
}
