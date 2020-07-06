<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\ReservationResponseTransfer;

/**
 * Provides the ability to get reservation data about product.
 */
interface OmsReservationReaderStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if this plugin is applicable for provided ReservationRequest.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(ReservationRequestTransfer $reservationRequestTransfer): bool;

    /**
     * Specification:
     * - Returns ReservationResponse.reservationQuantity for provided ReservationRequest.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReservationResponseTransfer
     */
    public function getReservationQuantity(ReservationRequestTransfer $reservationRequestTransfer): ReservationResponseTransfer;
}
