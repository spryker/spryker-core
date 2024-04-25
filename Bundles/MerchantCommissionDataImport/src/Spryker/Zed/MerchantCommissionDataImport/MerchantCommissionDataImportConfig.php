<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantCommissionDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\DataImportConfig;

class MerchantCommissionDataImportConfig extends DataImportConfig
{
    /**
     * @api
     *
     * @var string
     */
    public const IMPORT_TYPE_MERCHANT_COMMISSION_GROUP = 'merchant-commission-group';

    /**
     * @api
     *
     * @var string
     */
    public const IMPORT_TYPE_MERCHANT_COMMISSION = 'merchant-commission';

    /**
     * @api
     *
     * @var string
     */
    public const IMPORT_TYPE_MERCHANT_COMMISSION_AMOUNT = 'merchant-commission-amount';

    /**
     * @api
     *
     * @var string
     */
    public const IMPORT_TYPE_MERCHANT_COMMISSION_STORE = 'merchant-commission-store';

    /**
     * @api
     *
     * @var string
     */
    public const IMPORT_TYPE_MERCHANT_COMMISSION_MERCHANT = 'merchant-commission-merchant';

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getMerchantCommissionGroupDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        return $this->buildImporterConfiguration(
            $this->getModuleDataImportDirectory() . 'merchant_commission_group.csv',
            static::IMPORT_TYPE_MERCHANT_COMMISSION_GROUP,
        );
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getMerchantCommissionDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        return $this->buildImporterConfiguration(
            $this->getModuleDataImportDirectory() . 'merchant_commission.csv',
            static::IMPORT_TYPE_MERCHANT_COMMISSION,
        );
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getMerchantCommissionAmountDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        return $this->buildImporterConfiguration(
            $this->getModuleDataImportDirectory() . 'merchant_commission_amount.csv',
            static::IMPORT_TYPE_MERCHANT_COMMISSION_AMOUNT,
        );
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getMerchantCommissionStoreDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        return $this->buildImporterConfiguration(
            $this->getModuleDataImportDirectory() . 'merchant_commission_store.csv',
            static::IMPORT_TYPE_MERCHANT_COMMISSION_STORE,
        );
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getMerchantCommissionMerchantDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        return $this->buildImporterConfiguration(
            $this->getModuleDataImportDirectory() . 'merchant_commission_merchant.csv',
            static::IMPORT_TYPE_MERCHANT_COMMISSION_MERCHANT,
        );
    }

    /**
     * @return string
     */
    protected function getModuleDataImportDirectory(): string
    {
        return $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;
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
