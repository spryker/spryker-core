<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Business;

use Generated\Shared\Transfer\DataExportResultTransfer;
use Spryker\Service\DataExport\DataExportService;
use Spryker\Zed\Kernel\BundleConfigResolverAwareTrait;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\SalesDataExport\Business\Exporter\OrderExpenseCsvExporter;
use Spryker\Zed\SalesDataExport\Business\Exporter\OrderItemCsvExporter;
use Spryker\Zed\SalesDataExport\Business\Exporter\OrderCsvExporter;
use Spryker\Zed\SalesDataExport\Business\Reader\OrderExpenseReader;
use Spryker\Zed\SalesDataExport\Business\Reader\OrderItemReader;
use Spryker\Zed\SalesDataExport\Business\Reader\OrderReader;

/**
 * @method \Spryker\Zed\SalesDataExport\Business\SalesDataExportBusinessFactory getFactory()
 * @method \Spryker\Zed\SalesDataExport\SalesDataExportConfig getConfig()
 */
class SalesDataExportFacade extends AbstractFacade implements SalesDataExportFacadeInterface
{
    use BundleConfigResolverAwareTrait;

    /**
     * Specification
     * - Exports orders in various (writer) formats
     * - Returns results of export
     *
     * @param array $exportConfiguration
     *
     * @return DataExportResultTransfer
     */
    public function exportOrderBatch(array $exportConfiguration): DataExportResultTransfer
    {
        return (new OrderCsvExporter(new OrderReader(), new DataExportService()))
            ->exportBatch(($exportConfiguration));
    }

    /**
     * Specification
     * - Exports order items in various (writer) formats
     * - Returns results of export
     *
     * @param array $exportConfiguration
     *
     * @return DataExportResultTransfer
     */
    public function exportOrderItemBatch(array $exportConfiguration): DataExportResultTransfer
    {
        return (new OrderItemCsvExporter(new OrderItemReader(), new DataExportService()))
            ->exportBatch($exportConfiguration);
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
