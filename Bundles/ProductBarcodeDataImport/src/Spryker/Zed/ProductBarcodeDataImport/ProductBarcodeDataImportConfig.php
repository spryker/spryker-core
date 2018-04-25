<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcodeDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\DataImportConfig;

class ProductBarcodeDataImportConfig extends DataImportConfig
{
    public const IMPORT_TYPE_PRODUCT_BARCODE = 'product-barcode';
    public const IMPORT_FROM_FILE_NAME = 'product-barcode.csv';

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductBarcodeDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;

        return $this->buildImporterConfiguration($moduleDataImportDirectory . static::IMPORT_FROM_FILE_NAME, static::IMPORT_TYPE_PRODUCT_BARCODE);
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
