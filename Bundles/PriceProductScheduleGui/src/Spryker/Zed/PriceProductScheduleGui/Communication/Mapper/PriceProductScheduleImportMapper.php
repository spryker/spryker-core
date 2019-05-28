<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Mapper;

use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;
use Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig;

class PriceProductScheduleImportMapper implements PriceProductScheduleImportMapperInterface
{
    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig
     */
    protected $priceProductScheduleGuiConfig;

    /**
     * @param \Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig $priceProductScheduleGuiConfig
     */
    public function __construct(
        PriceProductScheduleGuiConfig $priceProductScheduleGuiConfig
    ) {
        $this->priceProductScheduleGuiConfig = $priceProductScheduleGuiConfig;
    }

    /**
     * @param array $importData
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleImportTransfer
     */
    public function mapArrayToPriceProductScheduleTransfer(
        array $importData,
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
    ): PriceProductScheduleImportTransfer {
        $preparedImportData = [];
        $fieldsMap = $this->priceProductScheduleGuiConfig->getImportFileToTransferFieldsMap();

        foreach ($importData as $key => $value) {
            $preparedImportData[$fieldsMap[$key]] = empty($value) ? null : $value;
        }

        return $priceProductScheduleImportTransfer->fromArray($preparedImportData);
    }
}
