<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\DataImportConfig;

class MerchantProductDataImportConfig extends DataImportConfig
{
    /**
     * @var string
     */
    public const IMPORT_TYPE_MERCHANT_PRODUCT = 'merchant-product';

    /**
     * @var string
     */
    public const IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT = 'merchant-combined-product';

    /**
     * @var string
     */
    public const COMBINED_MERCHANT_PRODUCT_ROW_IDENTIFIER = 'abstract_sku';

    /**
     * @var string
     */
    public const ASSIGNABLE_PRODUCT_TYPE_ABSTRACT = 'abstract';

    /**
     * @var string
     */
    public const ASSIGNABLE_PRODUCT_TYPE_CONCRETE = 'concrete';

    /**
     * @var string
     */
    public const ASSIGNABLE_PRODUCT_TYPE_BOTH = 'both';

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getMerchantProductDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;

        return $this->buildImporterConfiguration(
            $moduleDataImportDirectory . 'merchant_product.csv',
            static::IMPORT_TYPE_MERCHANT_PRODUCT,
        );
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCombinedMerchantProductRowIdentifier(): string
    {
        return static::COMBINED_MERCHANT_PRODUCT_ROW_IDENTIFIER;
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
