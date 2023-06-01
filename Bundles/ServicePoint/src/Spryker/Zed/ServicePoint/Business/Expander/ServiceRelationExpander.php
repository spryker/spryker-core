<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ServiceCollectionTransfer;
use Generated\Shared\Transfer\ServiceConditionsTransfer;
use Generated\Shared\Transfer\ServiceCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointCollectionTransfer;
use Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface;

class ServiceRelationExpander implements ServiceRelationExpanderInterface
{
    /**
     * @var \Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface
     */
    protected ServicePointRepositoryInterface $servicePointRepository;

    /**
     * @param \Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface $servicePointRepository
     */
    public function __construct(ServicePointRepositoryInterface $servicePointRepository)
    {
        $this->servicePointRepository = $servicePointRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointCollectionTransfer $servicePointCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointCollectionTransfer
     */
    public function expandServicePointCollectionWithServiceRelations(
        ServicePointCollectionTransfer $servicePointCollectionTransfer
    ): ServicePointCollectionTransfer {
        $servicePointUuids = $this->extractServicePointUuidsFromServicePointTransfers($servicePointCollectionTransfer);

        $serviceCollectionTransfer = $this->getServiceCollection($servicePointUuids);
        if (!count($serviceCollectionTransfer->getServices())) {
            return $servicePointCollectionTransfer;
        }

        $serviceTransfersGroupedByServicePointUuid = $this->getServiceTransfersGroupedByServicePointUuid($serviceCollectionTransfer);

        return $this->addServiceRelationsToServicePointCollection(
            $servicePointCollectionTransfer,
            $serviceTransfersGroupedByServicePointUuid,
        );
    }

    /**
     * @param list<string> $servicePointUuids
     *
     * @return \Generated\Shared\Transfer\ServiceCollectionTransfer
     */
    protected function getServiceCollection(array $servicePointUuids): ServiceCollectionTransfer
    {
        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())->setServiceConditions(
            (new ServiceConditionsTransfer())->setServicePointUuids($servicePointUuids),
        );

        return $this->servicePointRepository->getServiceCollection($serviceCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointCollectionTransfer $servicePointCollectionTransfer
     *
     * @return list<string>
     */
    protected function extractServicePointUuidsFromServicePointTransfers(ServicePointCollectionTransfer $servicePointCollectionTransfer): array
    {
        $servicePointUuids = [];
        foreach ($servicePointCollectionTransfer->getServicePoints() as $servicePointTransfer) {
            $servicePointUuids[] = $servicePointTransfer->getUuidOrFail();
        }

        return $servicePointUuids;
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceCollectionTransfer $serviceCollectionTransfer
     *
     * @return array<string, list<\Generated\Shared\Transfer\ServiceTransfer>>
     */
    protected function getServiceTransfersGroupedByServicePointUuid(ServiceCollectionTransfer $serviceCollectionTransfer): array
    {
        $serviceTransfersGroupedByServicePointUuid = [];
        foreach ($serviceCollectionTransfer->getServices() as $serviceTransfer) {
            $serviceTransfersGroupedByServicePointUuid[$serviceTransfer->getServicePointOrFail()->getUuidOrFail()][] = $serviceTransfer;
        }

        return $serviceTransfersGroupedByServicePointUuid;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointCollectionTransfer $servicePointCollectionTransfer
     * @param array<string, list<\Generated\Shared\Transfer\ServiceTransfer>> $serviceTransfersGroupedByServicePointUuid
     *
     * @return \Generated\Shared\Transfer\ServicePointCollectionTransfer
     */
    protected function addServiceRelationsToServicePointCollection(
        ServicePointCollectionTransfer $servicePointCollectionTransfer,
        array $serviceTransfersGroupedByServicePointUuid
    ): ServicePointCollectionTransfer {
        foreach ($servicePointCollectionTransfer->getServicePoints() as $servicePointTransfer) {
            $serviceTransfers = $serviceTransfersGroupedByServicePointUuid[$servicePointTransfer->getUuidOrFail()] ?? [];
            $servicePointTransfer->setServices(new ArrayObject($serviceTransfers));
        }

        return $servicePointCollectionTransfer;
    }
}
