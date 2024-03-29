<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductDiscontinuedDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\DataImportConfig;

class ProductDiscontinuedDataImportConfig extends DataImportConfig
{
    /**
     * @uses \Spryker\Zed\ProductDiscontinued\ProductDiscontinuedConfig::DEFAULT_DAYS_AMOUNT_BEFORE_PRODUCT_DEACTIVATE
     *
     * @var int
     */
    public const DEFAULT_DAYS_AMOUNT_BEFORE_PRODUCT_DEACTIVATE = 180;

    /**
     * @var string
     */
    public const IMPORT_TYPE_PRODUCT_DISCONTINUED = 'product-discontinued';

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductDiscontinuedDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;

        return $this->buildImporterConfiguration(
            $moduleDataImportDirectory . 'product_discontinued.csv',
            static::IMPORT_TYPE_PRODUCT_DISCONTINUED,
        );
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
            . DIRECTORY_SEPARATOR . '..',
        );

        return $moduleRoot . DIRECTORY_SEPARATOR;
    }
}
