<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;

interface PriceProductScheduleDisablerInterface
{
    /**
     * @return void
     */
    public function disableNotActiveScheduledPrices(): void;

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function disableNotActiveScheduledPricesByIdProductAbstract(int $idProductAbstract): void;

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function disableNotActiveScheduledPricesByIdProductConcrete(int $idProductConcrete): void;

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return void
     */
    public function disableNotRelevantPriceProductSchedulesByPriceProductSchedule(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    public function disablePriceProductSchedule(PriceProductScheduleTransfer $priceProductScheduleTransfer): PriceProductScheduleTransfer;

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    public function deactivatePriceProductSchedule(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): PriceProductScheduleTransfer;
}
