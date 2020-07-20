<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ReservationRequestTransfer;

/**
 * Provides ability to check if plugins should be executed.
 *
 * Use this plugin to update availability of reserved products.
 */
interface ReservationPostSaveTerminationAwareStrategyPluginInterface
{
    /**
     * Specification:
     * - Returns true if execution of plugins should be terminated.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return bool
     */
    public function isTerminated(ReservationRequestTransfer $reservationRequestTransfer): bool;

    /**
     * Specification:
     * - Checks if plugin is applicable for a given ReservationRequestTransfer.
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
     *  - Handles all necessary events related to reservation updates.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function handle(ReservationRequestTransfer $reservationRequestTransfer): void;
}
