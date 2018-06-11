<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\ProductAlternativeDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\DataImportConfig;

class ProductAlternativeDataImportConfig extends DataImportConfig
{
    const IMPORT_TYPE_PRODUCT_ALTERNATIVE = 'product-alternative';

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductAlternativeDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;

        return $this->buildImporterConfiguration($moduleDataImportDirectory . 'product_alternative.csv', static::IMPORT_TYPE_PRODUCT_ALTERNATIVE);
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
