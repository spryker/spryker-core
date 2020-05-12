<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Business;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportReportTransfer;

interface SalesDataExportFacadeInterface
{
    /**
     * Specification
     * - Exports orders in various (writer) formats
     * - Returns results of export
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     */
    public function exportOrder(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportReportTransfer;

    /**
     * Specification
     * - Exports order items in various (writer) formats
     * - Returns results of export
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     */
    public function exportOrderItem(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportReportTransfer;

    /**
     * Specification
     * - Exports order expense in various (writer) formats
     * - Returns results of export
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     */
    public function exportOrderExpense(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportReportTransfer;
}
