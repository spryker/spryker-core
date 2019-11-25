<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentDataImport\Communication\Plugin;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PaymentDataImport\PaymentDataImportConfig;

/**
 * @method \Spryker\Zed\PaymentDataImport\Business\PaymentDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\PaymentDataImport\PaymentDataImportConfig getConfig()
 */
class PaymentMethodDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritDoc}
     * - Imports payment method data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer {
        return $this->getFacade()->importPaymentMethod();
    }

    /**
     * {@inheritDoc}
     * - Returns the name of the payment method DataImporter.
     *
     * @api
     *
     * @return string
     */
    public function getImportType(): string
    {
        return PaymentDataImportConfig::IMPORT_TYPE_PAYMENT_METHOD;
    }
}
