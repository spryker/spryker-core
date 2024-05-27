<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionDataExport\Business;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportReportTransfer;

interface MerchantCommissionDataExportFacadeInterface
{
    /**
     * Specification:
     * - Exports merchant commissions according to configuration provided in `DataExportConfigurationTransfer`.
     * - Returns results of export in `DataExportReportTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     */
    public function exportMerchantCommission(
        DataExportConfigurationTransfer $dataExportConfigurationTransfer
    ): DataExportReportTransfer;
}
