<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\OrderItem;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Sales\Persistence\SalesRepositoryInterface;

class SalesOrderItemReader implements SalesOrderItemReaderInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface
     */
    protected $salesRepository;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface $salesRepository
     */
    public function __construct(SalesRepositoryInterface $salesRepository)
    {
        $this->salesRepository = $salesRepository;
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
     * @param \Propel\Runtime\Collection\ObjectCollection $salesOrderItemEntities
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]|\ArrayObject
     */
    protected function hydrateSalesOrderItemTransfersFromPersistence(ObjectCollection $salesOrderItemEntities): ArrayObject
    {
        $salesOrderItemTransfers = new ArrayObject();

        foreach ($salesOrderItemEntities as $itemEntity) {
            $itemTransfer = (new ItemTransfer())
                ->fromArray($itemEntity->toArray(), true);

            $salesOrderItemTransfers->append($itemTransfer);
        }

        return $salesOrderItemTransfers;
    }
}
