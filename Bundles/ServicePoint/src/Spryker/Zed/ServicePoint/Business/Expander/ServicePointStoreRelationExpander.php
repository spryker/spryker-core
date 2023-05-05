<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Expander;

use ArrayObject;
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
        $servicePointIds = $this->extractServicePointIdsFromServicePointTransfers(
            $servicePointCollectionTransfer->getServicePoints(),
        );

        $storeTransfersGroupedByIdServicePoint = $this->servicePointRepository
            ->getServicePointStoresGroupedByIdServicePoint($servicePointIds);

        $servicePointTransfers = $this->addStoreRelationsToServicePointTransfers(
            $servicePointCollectionTransfer->getServicePoints(),
            $storeTransfersGroupedByIdServicePoint,
        );

        return $servicePointCollectionTransfer->setServicePoints($servicePointTransfers);
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
}
