<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ServicePointAddressCollectionTransfer;
use Generated\Shared\Transfer\ServicePointCollectionTransfer;
use Generated\Shared\Transfer\ServicePointConditionsTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;
use Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface;

class ServicePointExpander implements ServicePointExpanderInterface
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
     * @param \Generated\Shared\Transfer\ServicePointAddressCollectionTransfer $servicePointAddressCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressCollectionTransfer
     */
    public function expandServicePointAddressCollectionWithServicePointIds(
        ServicePointAddressCollectionTransfer $servicePointAddressCollectionTransfer
    ): ServicePointAddressCollectionTransfer {
        $servicePointUuids = $this->extractServicePointUuidsFromServicePointAddressCollection($servicePointAddressCollectionTransfer);
        $servicePointIdsIndexedByServicePointUuid = $this->servicePointRepository->getServicePointIdsIndexedByServicePointUuid($servicePointUuids);

        foreach ($servicePointAddressCollectionTransfer->getServicePointAddresses() as $servicePointAddressTransfer) {
            $servicePointAddressTransfer->getServicePointOrFail()->setIdServicePoint(
                $servicePointIdsIndexedByServicePointUuid[$servicePointAddressTransfer->getServicePointOrFail()->getUuidOrFail()],
            );
        }

        return $servicePointAddressCollectionTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer> $serviceTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer>
     */
    public function expandServicesWithServicePoints(
        ArrayObject $serviceTransfers
    ): ArrayObject {
        $servicePointUuids = $this->extractServicePointUuidsFromServiceTransfers($serviceTransfers);
        $servicePointTransfersIndexedByUuids = $this->getServicePointTransfersIndexedByUuids($servicePointUuids);

        foreach ($serviceTransfers as $serviceTransfer) {
            $servicePointUuid = $serviceTransfer->getServicePointOrFail()->getUuidOrFail();

            $serviceTransfer->setServicePoint(
                $servicePointTransfersIndexedByUuids[$servicePointUuid],
            );
        }

        return $serviceTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressCollectionTransfer $servicePointAddressCollectionTransfer
     *
     * @return list<string>
     */
    protected function extractServicePointUuidsFromServicePointAddressCollection(
        ServicePointAddressCollectionTransfer $servicePointAddressCollectionTransfer
    ): array {
        $servicePointUuids = [];

        foreach ($servicePointAddressCollectionTransfer->getServicePointAddresses() as $servicePointAddressTransfer) {
            $servicePointUuids[] = $servicePointAddressTransfer->getServicePointOrFail()->getUuidOrFail();
        }

        return $servicePointUuids;
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
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer> $serviceTransfers
     *
     * @return list<string>
     */
    protected function extractServicePointUuidsFromServiceTransfers(
        ArrayObject $serviceTransfers
    ): array {
        $servicePointUuids = [];

        foreach ($serviceTransfers as $serviceTransfer) {
            $servicePointUuids[] = $serviceTransfer->getServicePointOrFail()->getUuidOrFail();
        }

        return $servicePointUuids;
    }
}
