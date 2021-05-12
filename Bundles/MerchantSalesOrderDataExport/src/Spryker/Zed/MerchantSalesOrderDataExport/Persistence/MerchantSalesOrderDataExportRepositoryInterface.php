<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderDataExport\Persistence;

use Generated\Shared\Transfer\DataExportBatchTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;

interface MerchantSalesOrderDataExportRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportBatchTransfer
     */
    public function getMerchantOrderData(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportBatchTransfer;

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportBatchTransfer
     */
    public function getMerchantOrderItemData(
        DataExportConfigurationTransfer $dataExportConfigurationTransfer
    ): DataExportBatchTransfer;

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportBatchTransfer
     */
    public function getMerchantOrderExpenseData(
        DataExportConfigurationTransfer $dataExportConfigurationTransfer
    ): DataExportBatchTransfer;
}
