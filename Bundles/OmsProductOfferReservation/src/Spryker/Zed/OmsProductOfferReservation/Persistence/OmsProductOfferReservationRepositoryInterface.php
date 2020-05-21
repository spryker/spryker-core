<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsProductOfferReservation\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer;
use Generated\Shared\Transfer\OmsProductOfferReservationTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;

interface OmsProductOfferReservationRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OmsProductOfferReservationTransfer|null
     */
    public function find(
        ReservationRequestTransfer $reservationRequestTransfer
    ): ?OmsProductOfferReservationTransfer;

    /**
     * @param \Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer $omsProductOfferReservationCriteriaTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getQuantity(
        OmsProductOfferReservationCriteriaTransfer $omsProductOfferReservationCriteriaTransfer
    ): Decimal;

    /**
     * @param string $sku
     * @param \ArrayObject|\Generated\Shared\Transfer\OmsStateTransfer[] $omsStateTransfers
     * @param string|null $productOfferReference
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[]
     */
    public function getAggregatedReservations(
        string $sku,
        ArrayObject $omsStateTransfers,
        ?string $productOfferReference = null,
        ?StoreTransfer $storeTransfer = null
    ): array;
}
