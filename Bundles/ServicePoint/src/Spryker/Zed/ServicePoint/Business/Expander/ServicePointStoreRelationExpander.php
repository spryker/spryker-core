<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ServiceCollectionTransfer;
use Generated\Shared\Transfer\ServicePointCollectionTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface;

class ServicePointStoreRelationExpander implements ServicePointStoreRelationExpanderInterface
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
    public function expandServicePointCollectionWithStoreRelations(
        ServicePointCollectionTransfer $servicePointCollectionTransfer
    ): ServicePointCollectionTransfer {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointTransfer> $servicePointTransfers */
        $servicePointTransfers = $servicePointCollectionTransfer->getServicePoints();
        $servicePointIds = $this->extractServicePointIdsFromServicePointTransfers($servicePointTransfers);

        $storeTransfersGroupedByIdServicePoint = $this->servicePointRepository
            ->getServicePointStoresGroupedByIdServicePoint($servicePointIds);

        $servicePointTransfers = $this->addStoreRelationsToServicePointTransfers(
            $servicePointTransfers,
            $storeTransfersGroupedByIdServicePoint,
        );

        return $servicePointCollectionTransfer->setServicePoints($servicePointTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceCollectionTransfer $serviceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceCollectionTransfer
     */
    public function expandServiceCollectionWithServicePointStoreRelations(
        ServiceCollectionTransfer $serviceCollectionTransfer
    ): ServiceCollectionTransfer {
        $servicePointCollectionTransfer = $this->extractServicePointCollectionFromServiceCollection($serviceCollectionTransfer);
        $servicePointCollectionTransfer = $this->expandServicePointCollectionWithStoreRelations($servicePointCollectionTransfer);

        $servicePointTransfersIndexedByServicePointIds = $this->getServicePointTransfersIndexedByServicePointIds($servicePointCollectionTransfer);

        foreach ($serviceCollectionTransfer->getServices() as $serviceTransfer) {
            $serviceTransfer->setServicePoint(
                $servicePointTransfersIndexedByServicePointIds[$serviceTransfer->getServicePointOrFail()->getIdServicePointOrFail()],
            );
        }

        return $serviceCollectionTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointTransfer> $servicePointTransfers
     * @param array<int, list<\Generated\Shared\Transfer\StoreTransfer>> $storeTransfersGroupedByIdServicePoint
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointTransfer>
     */
    protected function addStoreRelationsToServicePointTransfers(
        ArrayObject $servicePointTransfers,
        array $storeTransfersGroupedByIdServicePoint
    ): ArrayObject {
        foreach ($servicePointTransfers as $servicePointTransfer) {
            if (!isset($storeTransfersGroupedByIdServicePoint[$servicePointTransfer->getIdServicePointOrFail()])) {
                continue;
            }

            $storeTransfers = new ArrayObject(
                $storeTransfersGroupedByIdServicePoint[$servicePointTransfer->getIdServicePointOrFail()],
            );

            $servicePointTransfer->setStoreRelation(
                (new StoreRelationTransfer())->setStores($storeTransfers),
            );
        }

        return $servicePointTransfers;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointTransfer> $servicePointTransfers
     *
     * @return list<int>
     */
    protected function extractServicePointIdsFromServicePointTransfers(ArrayObject $servicePointTransfers): array
    {
        $servicePointIds = [];

        foreach ($servicePointTransfers as $servicePointTransfer) {
            $servicePointIds[] = $servicePointTransfer->getIdServicePointOrFail();
        }

        return $servicePointIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceCollectionTransfer $serviceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointCollectionTransfer
     */
    protected function extractServicePointCollectionFromServiceCollection(ServiceCollectionTransfer $serviceCollectionTransfer): ServicePointCollectionTransfer
    {
        $servicePointTransfers = [];
        foreach ($serviceCollectionTransfer->getServices() as $serviceTransfer) {
            if (array_key_exists($serviceTransfer->getServicePointOrFail()->getIdServicePointOrFail(), $servicePointTransfers)) {
                continue;
            }

            $servicePointTransfers[$serviceTransfer->getServicePointOrFail()->getIdServicePointOrFail()] = $serviceTransfer->getServicePointOrFail();
        }

        return (new ServicePointCollectionTransfer())->setServicePoints(new ArrayObject($servicePointTransfers));
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointCollectionTransfer $servicePointCollectionTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\ServicePointTransfer>
     */
    protected function getServicePointTransfersIndexedByServicePointIds(ServicePointCollectionTransfer $servicePointCollectionTransfer): array
    {
        $servicePointTransfersIndexedByServicePointIds = [];
        foreach ($servicePointCollectionTransfer->getServicePoints() as $servicePointTransfer) {
            $servicePointTransfersIndexedByServicePointIds[$servicePointTransfer->getIdServicePointOrFail()] = $servicePointTransfer;
        }

        return $servicePointTransfersIndexedByServicePointIds;
    }
}
