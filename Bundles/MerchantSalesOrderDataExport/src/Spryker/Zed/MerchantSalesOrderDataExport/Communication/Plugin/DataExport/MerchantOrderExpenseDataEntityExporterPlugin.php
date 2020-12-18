<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderDataExport\Communication\Plugin\DataExport;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportReportTransfer;
use Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityExporterPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantSalesOrderDataExport\Business\MerchantSalesOrderDataExportFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantSalesOrderDataExport\MerchantSalesOrderDataExportConfig getConfig()
 */
class MerchantOrderExpenseDataEntityExporterPlugin extends AbstractPlugin implements DataEntityExporterPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getDataEntity(): string
    {
        return 'merchant-order-expense';
    }

    /**
     * {@inheritDoc}
     * - Exports merchant order expenses data according to configuration in `DataExportConfigurationTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     */
    public function export(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportReportTransfer
    {
        return $this->getFacade()->exportMerchantOrderExpense($dataExportConfigurationTransfer);
    }
}
