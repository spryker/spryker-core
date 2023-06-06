<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\DataImportConfig;

class ProductOfferShipmentTypeDataImportConfig extends DataImportConfig
{
    /**
     * @api
     *
     * @var string
     */
    public const IMPORT_TYPE_PRODUCT_OFFER_SHIPMENT_TYPE = 'product-offer-shipment-type';

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductOfferShipmentTypeDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        return $this->buildImporterConfiguration(
            $this->getModuleDataImportDirectory() . 'product_offer_shipment_type.csv',
            static::IMPORT_TYPE_PRODUCT_OFFER_SHIPMENT_TYPE,
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

    /**
     * @return string
     */
    protected function getModuleDataImportDirectory(): string
    {
        return $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;
    }
}
