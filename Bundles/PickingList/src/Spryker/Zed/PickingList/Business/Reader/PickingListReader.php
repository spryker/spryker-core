<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListCriteriaTransfer;
use Spryker\Zed\PickingList\Business\Expander\PickingListExpanderInterface;
use Spryker\Zed\PickingList\Business\Mapper\PickingListMapperInterface;
use Spryker\Zed\PickingList\Persistence\PickingListRepositoryInterface;

class PickingListReader implements PickingListReaderInterface
{
    /**
     * @var \Spryker\Zed\PickingList\Persistence\PickingListRepositoryInterface
     */
    protected PickingListRepositoryInterface $pickingListRepository;

    /**
     * @var \Spryker\Zed\PickingList\Business\Expander\PickingListExpanderInterface
     */
    protected PickingListExpanderInterface $pickingListExpander;

    /**
     * @var \Spryker\Zed\PickingList\Business\Mapper\PickingListMapperInterface
     */
    protected PickingListMapperInterface $pickingListMapper;

    /**
     * @var list<\Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListCollectionExpanderPluginInterface>
     */
    protected array $pickingListCollectionExpanderPlugins;

    /**
     * @param \Spryker\Zed\PickingList\Persistence\PickingListRepositoryInterface $pickingListRepository
     * @param \Spryker\Zed\PickingList\Business\Expander\PickingListExpanderInterface $pickingListExpander
     * @param \Spryker\Zed\PickingList\Business\Mapper\PickingListMapperInterface $pickingListMapper
     * @param array<\Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListCollectionExpanderPluginInterface> $pickingListCollectionExpanderPlugins
     */
    public function __construct(
        PickingListRepositoryInterface $pickingListRepository,
        PickingListExpanderInterface $pickingListExpander,
        PickingListMapperInterface $pickingListMapper,
        array $pickingListCollectionExpanderPlugins
    ) {
        $this->pickingListRepository = $pickingListRepository;
        $this->pickingListExpander = $pickingListExpander;
        $this->pickingListMapper = $pickingListMapper;
        $this->pickingListCollectionExpanderPlugins = $pickingListCollectionExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCriteriaTransfer $pickingListCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function getPickingListCollection(
        PickingListCriteriaTransfer $pickingListCriteriaTransfer
    ): PickingListCollectionTransfer {
        $pickingListCollectionTransfer = $this->pickingListRepository->getPickingListCollection($pickingListCriteriaTransfer);
        $pickingListCollectionTransfer = $this->pickingListExpander->expandPickingListCollectionWithOrderItems(
            $pickingListCollectionTransfer,
        );

        return $this->executePickingListCollectionExpanderPlugins($pickingListCollectionTransfer);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     *
     * @return array<int, list<\Generated\Shared\Transfer\PickingListTransfer>>
     */
    public function getPickingListTransfersGroupedByIdSalesOrder(ArrayObject $orderTransfers): array
    {
        $pickingListCriteriaTransfer = $this->pickingListMapper->mapOrderCollectionToPickingListCriteriaTransfer(
            $orderTransfers,
            new PickingListCriteriaTransfer(),
        );

        $pickingListCollectionTransfer = $this->getPickingListCollection($pickingListCriteriaTransfer);
        $salesOrderIdsIndexedByOrderItemUuid = $this->getSalesOrderIdsIndexedByOrderItemUuid($orderTransfers);

        $pickingListTransfersGroupedByIdSalesOrder = [];
        foreach ($pickingListCollectionTransfer->getPickingLists() as $pickingListTransfer) {
            foreach ($pickingListTransfer->getPickingListItems() as $pickingListItemTransfer) {
                $idSalesOrder = $salesOrderIdsIndexedByOrderItemUuid[$pickingListItemTransfer->getOrderItemOrFail()->getUuidOrFail()] ?? null;
                if (!$idSalesOrder) {
                    continue;
                }

                $pickingListTransfersGroupedByIdSalesOrder[$idSalesOrder][] = $pickingListTransfer;

                break;
            }
        }

        return $pickingListTransfersGroupedByIdSalesOrder;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    protected function executePickingListCollectionExpanderPlugins(
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): PickingListCollectionTransfer {
        foreach ($this->pickingListCollectionExpanderPlugins as $pickingListCollectionExpanderPlugin) {
            $pickingListCollectionTransfer = $pickingListCollectionExpanderPlugin->expand($pickingListCollectionTransfer);
        }

        return $pickingListCollectionTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     *
     * @return array<string, int>
     */
    protected function getSalesOrderIdsIndexedByOrderItemUuid(ArrayObject $orderTransfers): array
    {
        $salesOrderIdsIndexedByOrderItemUuid = [];
        foreach ($orderTransfers as $orderTransfer) {
            $idSalesOrder = $orderTransfer->getIdSalesOrderOrFail();
            foreach ($orderTransfer->getItems() as $itemTransfer) {
                $salesOrderIdsIndexedByOrderItemUuid[$itemTransfer->getUuidOrFail()] = $idSalesOrder;
            }
        }

        return $salesOrderIdsIndexedByOrderItemUuid;
    }
}
