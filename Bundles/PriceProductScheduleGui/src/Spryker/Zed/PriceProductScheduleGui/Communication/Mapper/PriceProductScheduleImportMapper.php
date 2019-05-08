<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Mapper;

use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;

class PriceProductScheduleImportMapper implements PriceProductScheduleImportMapperInterface
{
    /**
     * @param array $importData
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     * @param array $fieldsMap
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleImportTransfer
     */
    public function mapArrayToPriceProductScheduleTransfer(
        array $importData,
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer,
        array $fieldsMap
    ): PriceProductScheduleImportTransfer {
        $preparedImportData = [];

        foreach ($importData as $key => $value) {
            $preparedImportData[$fieldsMap[$key]] = $value;
        }

        return $priceProductScheduleImportTransfer->fromArray($preparedImportData);
    }
}
