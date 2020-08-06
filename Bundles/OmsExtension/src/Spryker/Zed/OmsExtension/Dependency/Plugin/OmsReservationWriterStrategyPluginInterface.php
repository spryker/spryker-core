<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ReservationRequestTransfer;

/**
 * Provides the ability to save reservation data for product.
 */
interface OmsReservationWriterStrategyPluginInterface
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
     * - Saves reservation data for provided ReservationRequest.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function writeReservation(ReservationRequestTransfer $reservationRequestTransfer): void;
}
