<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Shared\MerchantProductDataImport\MerchantProductDataImportConstants;
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
     * @var list<string>
     */
    protected const POSSIBLE_CSV_HEADERS = [];

    /**
     * @var list<string>
     */
    protected const POSSIBLE_CSV_LOCALE_HEADERS = [];

    /**
     * @var list<string>
     */
    protected const POSSIBLE_CSV_ATTRIBUTE_HEADERS = [];

    /**
     * @var list<string>
     */
    protected const POSSIBLE_CSV_STOCK_HEADERS = [];

    /**
     * @var list<string>
     */
    protected const POSSIBLE_CSV_PRICE_HEADERS = [];

    /**
     * @var list<string>
     */
    protected const POSSIBLE_CSV_IMAGE_HEADERS = [];

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
     * Specification:
     * - Returns the filesystem name used for storing data import merchant uploaded files.
     *
     * @api
     *
     * @return string
     */
    public function getFileSystemName(): string
    {
        return $this->get(MerchantProductDataImportConstants::FILE_SYSTEM_NAME);
    }

    /**
     * Specification:
     * - Returns the list of possible CSV headers.
     *
     * @api
     *
     * @return list<string>
     */
    public function getPossibleCsvHeaders(): array
    {
        return static::POSSIBLE_CSV_HEADERS;
    }

    /**
     * Specification:
     * - Returns the list of possible CSV locale headers.
     *
     * @api
     *
     * @return list<string>
     */
    public function getPossibleCsvLocaleHeaders(): array
    {
        return static::POSSIBLE_CSV_LOCALE_HEADERS;
    }

    /**
     * Specification:
     * - Returns the list of possible CSV attribute headers.
     *
     * @api
     *
     * @return list<string>
     */
    public function getPossibleCsvAttributeHeaders(): array
    {
        return static::POSSIBLE_CSV_ATTRIBUTE_HEADERS;
    }

    /**
     * Specification:
     * - Returns the list of possible CSV stock headers.
     *
     * @api
     *
     * @return list<string>
     */
    public function getPossibleCsvStockHeaders(): array
    {
        return static::POSSIBLE_CSV_STOCK_HEADERS;
    }

    /**
     * Specification:
     * - Returns the list of possible CSV price headers.
     *
     * @api
     *
     * @return list<string>
     */
    public function getPossibleCsvPriceHeaders(): array
    {
        return static::POSSIBLE_CSV_PRICE_HEADERS;
    }

    /**
     * @api
     *
     * @return list<string>
     */
    public function getPossibleCsvImageHeaders(): array
    {
        return static::POSSIBLE_CSV_IMAGE_HEADERS;
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
