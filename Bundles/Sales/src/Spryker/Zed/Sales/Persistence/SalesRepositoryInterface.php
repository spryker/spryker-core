<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Generated\Shared\Transfer\OrderListRequestTransfer;
use Generated\Shared\Transfer\OrderListTransfer;

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
     * @param \Generated\Shared\Transfer\OrderListRequestTransfer $orderListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getCustomerOrderListByCustomerReference(OrderListRequestTransfer $orderListRequestTransfer): OrderListTransfer;

    /**
     * @param \Generated\Shared\Transfer\OrderItemFilterTransfer $orderItemFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getOrderItems(OrderItemFilterTransfer $orderItemFilterTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function searchOrders(OrderListTransfer $orderListTransfer): OrderListTransfer;

    /**
     * @param int[] $salesOrderIds
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getSalesOrderItemsByOrderIds(array $salesOrderIds): array;

    /**
     * @param int[] $salesOrderIds
     *
     * @return \Generated\Shared\Transfer\TotalsTransfer[]
     */
    public function getMappedSalesOrderTotalsBySalesOrderIds(array $salesOrderIds): array;

    /**
     * @param int[] $salesOrderIds
     *
     * @return string[]
     */
    public function getCurrencyIsoCodesBySalesOrderIds(array $salesOrderIds): array;
}
