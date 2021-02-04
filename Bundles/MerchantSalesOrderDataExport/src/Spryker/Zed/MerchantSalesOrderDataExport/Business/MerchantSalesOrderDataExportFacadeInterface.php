<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderDataExport\Business;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportReportTransfer;

interface MerchantSalesOrderDataExportFacadeInterface
{
    /**
     * Specification:
     * - Exports merchant orders according to configuration in `DataExportConfigurationTransfer`.
     * - Merges module level configuration with provided `DataExportConfigurationTransfer`.
     * - Returns results of export in `DataExportReportTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     */
    public function exportMerchantOrder(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportReportTransfer;

    /**
     * Specification:
     * - Exports merchant order items according to configuration in `DataExportConfigurationTransfer`.
     * - Merges module level configuration with provided `DataExportConfigurationTransfer`.
     * - Returns results of export in `DataExportReportTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     */
    public function exportMerchantOrderItem(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportReportTransfer;

    /**
     * Specification:
     * - Exports merchant order expenses according to configuration in `DataExportConfigurationTransfer`.
     * - Merges module level configuration with provided `DataExportConfigurationTransfer`.
     * - Returns results of export in `DataExportReportTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     */
    public function exportMerchantOrderExpense(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportReportTransfer;
}
