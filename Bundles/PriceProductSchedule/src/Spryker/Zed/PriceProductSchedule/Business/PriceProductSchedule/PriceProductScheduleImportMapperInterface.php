<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule;

use Generated\Shared\Transfer\PriceProductScheduleCriteriaFilterTransfer;
use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;

interface PriceProductScheduleImportMapperInterface
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
    ): PriceProductScheduleCriteriaFilterTransfer;

    /**
     * @param array $importData
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleImportTransfer
     */
    public function mapPriceProductScheduleRowToPriceProductScheduleImportTransfer(
        array $importData,
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
    ): PriceProductScheduleImportTransfer;
}
