<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\DataImportConfig;

class PriceProductScheduleDataImportConfig extends DataImportConfig
{
    public const IMPORT_TYPE_PRODUCT_PRICE_SCHEDULE = 'product-price-schedule';
    public const PRICE_PRODUCT_SCHEDULE_LIST_DEFAULT_NAME = 'price schedule data import';

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getPriceProductScheduleDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;

        return $this->buildImporterConfiguration(
            $moduleDataImportDirectory . 'product_price_schedule.csv',
            static::IMPORT_TYPE_PRODUCT_PRICE_SCHEDULE
        );
    }

    /**
     * @return string
     */
    public function getPriceProductScheduleListDefaultName(): string
    {
        return static::PRICE_PRODUCT_SCHEDULE_LIST_DEFAULT_NAME;
    }

    /**
     * @return string
     */
    protected function getModuleRoot(): string
    {
        $moduleRoot = realpath(
            __DIR__
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
        );

        return $moduleRoot . DIRECTORY_SEPARATOR;
    }
}
