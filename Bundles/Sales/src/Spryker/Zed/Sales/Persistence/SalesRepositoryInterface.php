<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence;

use Generated\Shared\Transfer\AddressTransfer;
use Propel\Runtime\Collection\ObjectCollection;

interface SalesRepositoryInterface
{
    /**
     * @param string $customerReference
     * @param string $orderReference
     *
     * @return int|null
     */
    public function findCustomerOrderIdByOrderReference(string $customerReference, string $orderReference): ?int;

    /**
     * @param int $idOrderAddress
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|null
     */
    public function findOrderAddressByIdOrderAddress(int $idOrderAddress): ?AddressTransfer;

    /**
     * @param int $idSalesShipment
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findSalesOrderItemsBySalesShipmentId(int $idSalesShipment): ObjectCollection;
}
