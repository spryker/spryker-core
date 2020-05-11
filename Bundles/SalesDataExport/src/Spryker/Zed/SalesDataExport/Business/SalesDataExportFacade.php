<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Business;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportReportTransfer;
use Generated\Shared\Transfer\DataExportResultTransfer;
use Spryker\Service\DataExport\DataExportService;
use Spryker\Zed\Kernel\BundleConfigResolverAwareTrait;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\SalesDataExport\Business\Exporter\OrderExpenseCsvExporter;
use Spryker\Zed\SalesDataExport\Business\Exporter\OrderItemCsvExporter;
use Spryker\Zed\SalesDataExport\Business\Exporter\OrderCsvExporter;
use Spryker\Zed\SalesDataExport\Business\Reader\OrderExpenseReader;
use Spryker\Zed\SalesDataExport\Business\Reader\OrderItemCsvReader;
use Spryker\Zed\SalesDataExport\Business\Reader\OrderCsvReader;

/**
 * @method \Spryker\Zed\SalesDataExport\Business\SalesDataExportBusinessFactory getFactory()
 * @method \Spryker\Zed\SalesDataExport\SalesDataExportConfig getConfig()
 */
class SalesDataExportFacade extends AbstractFacade implements SalesDataExportFacadeInterface
{
    use BundleConfigResolverAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     */
    public function exportOrder(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportReportTransfer
    {
        return $this->getFactory()
            ->createOrderCsvExporter()
            ->export($dataExportConfigurationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfern
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     */
    public function exportOrderItem(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportReportTransfer
    {
        return $this->getFactory()
            ->createOrderItemCsvExporter()
            ->export($dataExportConfigurationTransfer);
    }


    /**
     * Specification
     * - Exports order expense in various (writer) formats
     * - Returns results of export
     *
     * @param array $exportConfiguration
     *
     * @return DataExportResultTransfer
     */
    public function exportOrderExpenseBatch(array $exportConfiguration): DataExportResultTransfer
    {
        return (new OrderExpenseCsvExporter(new OrderExpenseReader(), new DataExportService()))
            ->exportBatch($exportConfiguration);
    }
}
