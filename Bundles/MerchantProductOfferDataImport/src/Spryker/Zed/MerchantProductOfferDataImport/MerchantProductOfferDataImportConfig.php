<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Shared\MerchantProductOfferDataImport\MerchantProductOfferDataImportConstants;
use Spryker\Zed\DataImport\DataImportConfig;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Exception\MerchantProductOfferStorageNameNotConfiguredException;

class MerchantProductOfferDataImportConfig extends DataImportConfig
{
    /**
     * @var string
     */
    public const IMPORT_TYPE_MERCHANT_PRODUCT_OFFER = 'merchant-product-offer';

    /**
     * @var string
     */
    public const IMPORT_TYPE_MERCHANT_PRODUCT_OFFER_STORE = 'merchant-product-offer-store';

    /**
     * @var string
     */
    public const IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT_OFFER = 'merchant-combined-product-offer';

    /**
     * @var string
     */
    protected const MERCHANT_COMBINED_PRODUCT_OFFER_DATA_SET_IDENTIFIER = 'offer_reference';

    /**
     * @var int
     */
    protected const BULK_SIZE_MERCHANT_COMBINED_PRODUCT_OFFER_IMPORT = 5000;

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getMerchantProductOfferDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;

        return $this->buildImporterConfiguration($moduleDataImportDirectory . 'merchant_product_offer.csv', static::IMPORT_TYPE_MERCHANT_PRODUCT_OFFER);
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getMerchantProductOfferStoreDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;

        return $this->buildImporterConfiguration($moduleDataImportDirectory . 'merchant_product_offer_store.csv', static::IMPORT_TYPE_MERCHANT_PRODUCT_OFFER_STORE);
    }

    /**
     * Specification:
     * - Returns the import type name for the merchant combined product offer import.
     *
     * @api
     */
    public function getImportTypeMerchantCombinedProductOffer(): string
    {
        return static::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT_OFFER;
    }

    /**
     * Specification:
     * - Returns name of the field that identifies a merchant combined product offer data set.
     *
     * @api
     */
    public function getMerchantCombinedProductOfferDataSetIdentifier(): string
    {
        return static::MERCHANT_COMBINED_PRODUCT_OFFER_DATA_SET_IDENTIFIER;
    }

    /**
     * Specification:
     * - Returns the filesystem name for storing merchant uploaded files for combined product offer data import.
     * - Throws `MerchantProductOfferStorageNameNotConfiguredException` if the filesystem name is not configured.
     *
     * @api
     */
    public function getFileSystemName(): string
    {
        return $this->get(MerchantProductOfferDataImportConstants::FILE_SYSTEM_NAME) ?? throw new MerchantProductOfferStorageNameNotConfiguredException();
    }

    /**
     * Specification:
     * - Returns the bulk size for the merchant combined product offer import.
     *
     * @api
     */
    public function getBulkSizeMerchantCombinedProductOfferImport(): int
    {
        return static::BULK_SIZE_MERCHANT_COMBINED_PRODUCT_OFFER_IMPORT;
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
