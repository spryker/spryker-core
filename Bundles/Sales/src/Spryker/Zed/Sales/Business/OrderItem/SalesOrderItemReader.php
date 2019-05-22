<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\OrderItem;

use ArrayObject;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesOrderItemMapperInterface;
use Spryker\Zed\Sales\Persistence\SalesRepositoryInterface;

class SalesOrderItemReader implements SalesOrderItemReaderInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface
     */
    protected $salesRepository;

    /**
     * @var \Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesOrderItemMapperInterface
     */
    protected $salesOrderItemMapper;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface $salesRepository
     * @param \Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesOrderItemMapperInterface $salesOrderItemMapper
     */
    public function __construct(
        SalesRepositoryInterface $salesRepository,
        SalesOrderItemMapperInterface $salesOrderItemMapper
    ) {
        $this->salesRepository = $salesRepository;
        $this->salesOrderItemMapper = $salesOrderItemMapper;
    }

    /**
     * @param int $idSalesShipment
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]|\ArrayObject
     */
    public function findSalesOrderItemsBySalesShipmentId(int $idSalesShipment): ArrayObject
    {
        $salesOrderItemEntities = $this->salesRepository->findSalesOrderItemsBySalesShipmentId($idSalesShipment);

        return $this->hydrateSalesOrderItemTransfersFromPersistence($salesOrderItemEntities);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]|\Propel\Runtime\Collection\ObjectCollection $salesOrderItemEntities
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]|\ArrayObject
     */
    protected function hydrateSalesOrderItemTransfersFromPersistence(ObjectCollection $salesOrderItemEntities): ArrayObject
    {
        $salesOrderItemTransfers = new ArrayObject();
        foreach ($salesOrderItemEntities as $spySalesOrderItemEntity) {
            $itemTransfer = $this->salesOrderItemMapper->mapSalesOrderItemEntityToItemTransfer($spySalesOrderItemEntity);
            $salesOrderItemTransfers->append($itemTransfer);
        }

        return $salesOrderItemTransfers;
    }
}
