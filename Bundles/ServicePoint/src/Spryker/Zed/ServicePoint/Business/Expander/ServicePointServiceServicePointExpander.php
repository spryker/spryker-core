<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ServicePointCollectionTransfer;
use Generated\Shared\Transfer\ServicePointConditionsTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;
use Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface;

class ServicePointServiceServicePointExpander implements ServicePointServiceServicePointExpanderInterface
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
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointServiceTransfer> $servicePointServiceTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointServiceTransfer>
     */
    public function expandServicePointServiceTransfersWithServicePointRelations(
        ArrayObject $servicePointServiceTransfers
    ): ArrayObject {
        $servicePointUuids = $this->extractServicePointUuidsFromServicePointServiceTransfers($servicePointServiceTransfers);
        $servicePointTransfersIndexedByUuids = $this->getServicePointTransfersIndexedByUuids($servicePointUuids);

        foreach ($servicePointServiceTransfers as $servicePointServiceTransfer) {
            $servicePointUuid = $servicePointServiceTransfer->getServicePointOrFail()->getUuidOrFail();

            $servicePointServiceTransfer->setServicePoint(
                $servicePointTransfersIndexedByUuids[$servicePointUuid],
            );
        }

        return $servicePointServiceTransfers;
    }

    /**
     * @param list<string> $servicePointUuids
     *
     * @return array<string, \Generated\Shared\Transfer\ServicePointTransfer>
     */
    protected function getServicePointTransfersIndexedByUuids(array $servicePointUuids): array
    {
        $servicePointTransfersIndexedByUuids = [];
        $servicePointCollectionTransfer = $this->getServicePointCollectionTransfer($servicePointUuids);

        foreach ($servicePointCollectionTransfer->getServicePoints() as $servicePointTransfer) {
            $servicePointTransfersIndexedByUuids[$servicePointTransfer->getUuidOrFail()] = $servicePointTransfer;
        }

        return $servicePointTransfersIndexedByUuids;
    }

    /**
     * @param list<string> $servicePointUuids
     *
     * @return \Generated\Shared\Transfer\ServicePointCollectionTransfer
     */
    protected function getServicePointCollectionTransfer(
        array $servicePointUuids
    ): ServicePointCollectionTransfer {
        $servicePointConditionsTransfer = (new ServicePointConditionsTransfer())->setUuids($servicePointUuids);
        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())->setServicePointConditions($servicePointConditionsTransfer);

        return $this->servicePointRepository->getServicePointCollection($servicePointCriteriaTransfer);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointServiceTransfer> $servicePointServiceTransfers
     *
     * @return list<string>
     */
    protected function extractServicePointUuidsFromServicePointServiceTransfers(
        ArrayObject $servicePointServiceTransfers
    ): array {
        $servicePointUuids = [];

        foreach ($servicePointServiceTransfers as $servicePointServiceTransfer) {
            $servicePointUuids[] = $servicePointServiceTransfer->getServicePointOrFail()->getUuidOrFail();
        }

        return $servicePointUuids;
    }
}
