<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferValidityDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\DataImportConfig;

class ProductOfferValidityDataImportConfig extends DataImportConfig
{
    public const IMPORT_TYPE_PRODUCT_OFFER_VALIDITY = 'product-offer-validity';

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductOfferValidityDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;

        return $this->buildImporterConfiguration(
            $moduleDataImportDirectory . 'product_offer_validity.csv',
            static::IMPORT_TYPE_PRODUCT_OFFER_VALIDITY
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
            . DIRECTORY_SEPARATOR . '..'
        );

        return $moduleRoot . DIRECTORY_SEPARATOR;
    }
}
