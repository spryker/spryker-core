<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence;

use Generated\Shared\Transfer\OmsProductReservationTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;

interface OmsRepositoryInterface
{
    /**
     * @param int[] $processIds
     * @param int[] $stateBlackList
     *
     * @return array
     */
    public function getMatrixOrderItems(array $processIds, array $stateBlackList): array;

    /**
     * @param string[] $stateNames
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[]
     */
    public function getSalesOrderAggregationBySkuAndStatesNames(array $stateNames, string $sku, ?StoreTransfer $storeTransfer = null): array;

    /**
     * @param string[] $concreteSkus
     * @param int $idStore
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getSumOmsReservedProductQuantityByConcreteProductSkusForStore(array $concreteSkus, int $idStore): Decimal;

    /**
     * @param int[] $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\ItemStateTransfer[]
     */
    public function getItemHistoryStatesByOrderItemIds(array $salesOrderItemIds): array;

    /**
     * @param \Generated\Shared\Transfer\OrderItemFilterTransfer $orderItemFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getOrderItems(OrderItemFilterTransfer $orderItemFilterTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OmsProductReservationTransfer|null
     */
    public function findProductReservation(ReservationRequestTransfer $reservationRequestTransfer): ?OmsProductReservationTransfer;

    /**
     * @param string $sku
     * @param int $idStore
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function findProductReservationQuantity(string $sku, int $idStore): Decimal;

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ReservationResponseTransfer[]
     */
    public function findProductReservationStores(string $sku): array;
}
