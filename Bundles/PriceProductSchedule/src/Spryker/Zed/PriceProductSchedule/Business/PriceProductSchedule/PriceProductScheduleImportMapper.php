<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule;

use Generated\Shared\Transfer\PriceProductScheduleCriteriaFilterTransfer;
use Generated\Shared\Transfer\PriceProductScheduleImportMetaDataTransfer;
use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;
use Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig;

class PriceProductScheduleImportMapper implements PriceProductScheduleImportMapperInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig
     */
    protected $priceProductScheduleConfig;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig $priceProductScheduleConfig
     */
    public function __construct(
        PriceProductScheduleConfig $priceProductScheduleConfig
    ) {
        $this->priceProductScheduleConfig = $priceProductScheduleConfig;
    }

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
     * @param array $importData
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleImportTransfer
     */
    public function mapPriceProductScheduleRowToPriceProductScheduleImportTransfer(
        array $importData,
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
    ): PriceProductScheduleImportTransfer {
        $preparedImportData = [];
        $fieldsMap = $this->priceProductScheduleConfig->getImportFileToTransferFieldsMap();

        foreach ($importData as $key => $value) {
            $preparedImportData[$fieldsMap[$key]] = empty($value) ? null : $value;
        }

        return $priceProductScheduleImportTransfer
            ->fromArray($preparedImportData)
            ->setMetaData(new PriceProductScheduleImportMetaDataTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductScheduleCriteriaFilterTransfer
     */
    protected function createPriceProductScheduleCriteriaFilterTransfer(): PriceProductScheduleCriteriaFilterTransfer
    {
        return new PriceProductScheduleCriteriaFilterTransfer();
    }
}
