<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\OrderItem;

use ArrayObject;
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
        return $this->salesRepository->findSalesOrderItemsBySalesShipmentId($idSalesShipment);
    }
}
