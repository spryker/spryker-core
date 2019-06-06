<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule;

use Generated\Shared\Transfer\PriceProductScheduleCriteriaFilterTransfer;
use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;

class PriceProductScheduleImportMapper implements PriceProductScheduleImportMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     * @param \Generated\Shared\Transfer\PriceProductScheduleCriteriaFilterTransfer $priceProductScheduleCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleCriteriaFilterTransfer
     */
    public function mapPriceProductScheduleImportTransferToPriceProductScheduleCriteriaFilterTransfer(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer,
        PriceProductScheduleCriteriaFilterTransfer $priceProductScheduleCriteriaFilterTransfer
    ): PriceProductScheduleCriteriaFilterTransfer {
        return $this->createPriceProductScheduleCriteriaFilterTransfer()
            ->fromArray($priceProductScheduleImportTransfer->toArray(), true);
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductScheduleCriteriaFilterTransfer
     */
    protected function createPriceProductScheduleCriteriaFilterTransfer(): PriceProductScheduleCriteriaFilterTransfer
    {
        return new PriceProductScheduleCriteriaFilterTransfer();
    }
}
