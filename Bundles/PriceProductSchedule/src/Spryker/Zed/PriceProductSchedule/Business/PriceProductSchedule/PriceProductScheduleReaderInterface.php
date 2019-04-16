<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;

interface PriceProductScheduleReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findSimilarPriceProductSchedulesToDisable(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): array;

    /**
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findPriceProductSchedulesToDisable(): array;

    /**
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findPriceProductSchedulesToEnableForCurrentStore(): array;
}
