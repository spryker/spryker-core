<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PaymentDataImport\Communication\Plugin;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PaymentDataImport\PaymentDataImportConfig;

/**
 * @method \Spryker\Zed\PaymentDataImport\Business\PaymentDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\PaymentDataImport\PaymentDataImportConfig getConfig()
 */
class PaymentMethodStoreDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritDoc}
     * - Imports payment method store data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null)
    {
        return $this->getFacade()->importPaymentMethodStore($dataImporterConfigurationTransfer);
    }

    /**
     * {@inheritDoc}
     * - Returns the name of the payment method store DataImporter.
     *
     * @api
     *
     * @return string
     */
    public function getImportType()
    {
        return PaymentDataImportConfig::IMPORT_TYPE_PAYMENT_METHOD_STORE;
    }
}
