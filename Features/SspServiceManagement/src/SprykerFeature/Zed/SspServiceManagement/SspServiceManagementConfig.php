<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterDataSourceConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\SspServiceManagement\SspServiceManagementConstants;
use SprykerFeature\Zed\SspServiceManagement\Business\Exception\DefaultMerchantNotConfiguredException;

/**
 * @method \SprykerFeature\Shared\SspServiceManagement\SspServiceManagementConfig getSharedConfig()
 */
class SspServiceManagementConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Import type for product shipment type.
     *
     * @api
     *
     * @var string
     */
    public const IMPORT_TYPE_PRODUCT_SHIPMENT_TYPE = 'product-shipment-type';

    /**
     * Specification:
     * - Import type for product abstract type.
     *
     * @api
     *
     * @var string
     */
    public const IMPORT_TYPE_PRODUCT_ABSTRACT_TYPE = 'product-abstract-type';

    /**
     * Specification:
     * - Import type for product abstract to product abstract type relation.
     *
     * @api
     *
     * @var string
     */
    public const IMPORT_TYPE_PRODUCT_ABSTRACT_TO_PRODUCT_ABSTRACT_TYPE = 'product-abstract-product-abstract-type';

    /**
     * @uses \Spryker\Shared\ShipmentType\ShipmentTypeConfig::SHIPMENT_TYPE_DELIVERY
     *
     * @var string
     */
    protected const DEFAULT_SHIPMENT_TYPE = 'delivery';

    /**
     * Specification:
     * - Import configuration for product shipment type.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterDataSourceConfigurationTransfer
     */
    public function getProductShipmentTypeDataImporterConfiguration(): DataImporterDataSourceConfigurationTransfer
    {
        return (new DataImporterDataSourceConfigurationTransfer())
            ->setImportType(static::IMPORT_TYPE_PRODUCT_SHIPMENT_TYPE)
            ->setFileName('product_shipment_type.csv')
            ->setModuleName('SspServiceManagement')
            ->setDirectory('/data/data/import/common/common/');
    }

    /**
     * Specification:
     * - Import configuration for product abstract type.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterDataSourceConfigurationTransfer
     */
    public function getProductAbstractTypeDataImporterConfiguration(): DataImporterDataSourceConfigurationTransfer
    {
        return (new DataImporterDataSourceConfigurationTransfer())
            ->setImportType(static::IMPORT_TYPE_PRODUCT_ABSTRACT_TYPE)
            ->setFileName('product_abstract_type.csv')
            ->setModuleName('SspServiceManagement')
            ->setDirectory('/data/data/import/common/common/');
    }

    /**
     * Specification:
     * - Import configuration for product abstract to product abstract type relation.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterDataSourceConfigurationTransfer
     */
    public function getProductAbstractToProductAbstractTypeDataImporterConfiguration(): DataImporterDataSourceConfigurationTransfer
    {
        return (new DataImporterDataSourceConfigurationTransfer())
            ->setImportType(static::IMPORT_TYPE_PRODUCT_ABSTRACT_TO_PRODUCT_ABSTRACT_TYPE)
            ->setFileName('product_abstract_product_abstract_type.csv')
            ->setModuleName('SspServiceManagement')
            ->setDirectory('/data/data/import/common/common/');
    }

    /**
     * @api
     *
     * @param string $fileName
     * @param string $importType
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function buildImporterConfiguration(
        string $fileName,
        string $importType
    ): DataImporterConfigurationTransfer {
        $dataImporterReaderConfiguration = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfiguration->setFileName($fileName);

        $dataImporterConfiguration = new DataImporterConfigurationTransfer();
        $dataImporterConfiguration
            ->setImportType($importType)
            ->setReaderConfiguration($dataImporterReaderConfiguration);

        return $dataImporterConfiguration;
    }

    /**
     * Specification:
     * - Returns the default shipment type key.
     * - The default shipment type key is used for new products.
     * - The default shipment type key is used for the cart items if no shipment type is set.
     *
     * @api
     *
     * @return string
     */
    public function getDefaultShipmentType(): string
    {
        return static::DEFAULT_SHIPMENT_TYPE;
    }

    /**
     * Specification:
     * - Returns a merchant reference that is used for product offer creation.
     *
     * @api
     *
     * @throws \SprykerFeature\Zed\SspServiceManagement\Business\Exception\DefaultMerchantNotConfiguredException
     *
     * @return string
     */
    public function getDefaultMerchantReference(): string
    {
        throw new DefaultMerchantNotConfiguredException();
    }

    /**
     * Specification:
     * - Returns the product service type name.
     *
     * @api
     *
     * @return string
     */
    public function getProductServiceTypeName(): string
    {
        return $this->getSharedConfig()->getProductServiceTypeName();
    }

    /**
     * @example The format of returned array is:
     * [
     *    'PAYMENT_METHOD_1' => StateMachineProcess_1',
     *    'PAYMENT_METHOD_2' => StateMachineProcess_2',
     * ]
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getPaymentMethodStatemachineProcessMapping(): array
    {
        return $this->get(SspServiceManagementConstants::PAYMENT_METHOD_STATEMACHINE_MAPPING, []);
    }
}
