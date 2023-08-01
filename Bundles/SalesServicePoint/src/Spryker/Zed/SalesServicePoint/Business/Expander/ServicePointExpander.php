<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesServicePoint\Business\Expander;

use Generated\Shared\Transfer\SalesOrderItemServicePointCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderItemServicePointConditionsTransfer;
use Generated\Shared\Transfer\SalesOrderItemServicePointCriteriaTransfer;
use Spryker\Zed\SalesServicePoint\Persistence\SalesServicePointRepositoryInterface;

class ServicePointExpander implements ServicePointExpanderInterface
{
    /**
     * @var \Spryker\Zed\SalesServicePoint\Persistence\SalesServicePointRepositoryInterface
     */
    protected SalesServicePointRepositoryInterface $salesServicePointRepository;

    /**
     * @param \Spryker\Zed\SalesServicePoint\Persistence\SalesServicePointRepositoryInterface $salesServicePointRepository
     */
    public function __construct(SalesServicePointRepositoryInterface $salesServicePointRepository)
    {
        $this->salesServicePointRepository = $salesServicePointRepository;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithServicePoint(array $itemTransfers): array
    {
        $salesOrderItemIds = $this->extractSalesOrderItemIds($itemTransfers);
        $salesOrderItemServicePointTransfersIndexedBySalesOrderItemIds = $this->getSalesOrderItemServicePointTransfersIndexedBySalesOrderItemIds(
            $salesOrderItemIds,
        );

        if (!$salesOrderItemServicePointTransfersIndexedBySalesOrderItemIds) {
            return $itemTransfers;
        }

        return $this->addSalesOrderItemServicePointTransfers(
            $itemTransfers,
            $salesOrderItemServicePointTransfersIndexedBySalesOrderItemIds,
        );
    }

    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param array<int, \Generated\Shared\Transfer\SalesOrderItemServicePointTransfer> $salesOrderItemServicePointTransfersIndexedBySalesOrderItemIds
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function addSalesOrderItemServicePointTransfers(
        array $itemTransfers,
        array $salesOrderItemServicePointTransfersIndexedBySalesOrderItemIds
    ): array {
        foreach ($itemTransfers as $itemTransfer) {
            $idSalesOrderItem = $itemTransfer->getIdSalesOrderItemOrFail();

            if (isset($salesOrderItemServicePointTransfersIndexedBySalesOrderItemIds[$idSalesOrderItem])) {
                $salesOrderItemServicePointTransfer = $salesOrderItemServicePointTransfersIndexedBySalesOrderItemIds[$idSalesOrderItem];

                $itemTransfer->setSalesOrderItemServicePoint($salesOrderItemServicePointTransfer);
            }
        }

        return $itemTransfers;
    }

    /**
     * @param list<int> $salesOrderItemIds
     *
     * @return array<int, \Generated\Shared\Transfer\SalesOrderItemServicePointTransfer>
     */
    protected function getSalesOrderItemServicePointTransfersIndexedBySalesOrderItemIds(
        array $salesOrderItemIds
    ): array {
        $salesOrderItemServicePointTransfersIndexedBySalesOrderItemIds = [];
        $salesOrderItemServicePointCollectionTransfer = $this->getSalesOrderItemServicePointCollection($salesOrderItemIds);

        foreach ($salesOrderItemServicePointCollectionTransfer->getSalesOrderItemServicePoints() as $salesOrderItemServicePoint) {
            $idSalesOrderItem = $salesOrderItemServicePoint->getIdSalesOrderItemOrFail();
            $salesOrderItemServicePointTransfersIndexedBySalesOrderItemIds[$idSalesOrderItem] = $salesOrderItemServicePoint;
        }

        return $salesOrderItemServicePointTransfersIndexedBySalesOrderItemIds;
    }

    /**
     * @param list<int> $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemServicePointCollectionTransfer
     */
    protected function getSalesOrderItemServicePointCollection(
        array $salesOrderItemIds
    ): SalesOrderItemServicePointCollectionTransfer {
        $salesOrderItemServicePointConditionsTransfer = (new SalesOrderItemServicePointConditionsTransfer())
            ->setSalesOrderItemIds($salesOrderItemIds);

        $salesOrderItemServicePointCriteriaTransfer = (new SalesOrderItemServicePointCriteriaTransfer())
            ->setSalesOrderItemServicePointConditions($salesOrderItemServicePointConditionsTransfer);

        return $this->salesServicePointRepository->getSalesOrderItemServicePointCollection($salesOrderItemServicePointCriteriaTransfer);
    }

    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<int>
     */
    protected function extractSalesOrderItemIds(array $itemTransfers): array
    {
        $salesOrderItemIds = [];

        foreach ($itemTransfers as $itemTransfer) {
            $salesOrderItemIds[] = $itemTransfer->getIdSalesOrderItemOrFail();
        }

        return $salesOrderItemIds;
    }
}
