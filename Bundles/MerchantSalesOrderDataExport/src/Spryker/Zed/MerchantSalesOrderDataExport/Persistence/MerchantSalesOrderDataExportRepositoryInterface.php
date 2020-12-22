<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderDataExport\Persistence;

use Generated\Shared\Transfer\DataExportBatchTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;

interface MerchantSalesOrderDataExportRepositoryInterface
{
    /**
     * @module Metchant
     *
     * @return string[]
     */
    public function getMerchantNames(): array;

    /**
     * @module MerchantSalesOrder
     * @module Sales
     * @module Country
     * @module Locale
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportBatchTransfer
     */
    public function getMerchantOrderData(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportBatchTransfer;

    /**
     * @module MerchantSalesOrder
     * @module Sales
     * @module Country
     * @module Shipment
     * @module Oms
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportBatchTransfer
     */
    public function getMerchantOrderItemData(
        DataExportConfigurationTransfer $dataExportConfigurationTransfer
    ): DataExportBatchTransfer;

    /**
     * @module MerchantSalesOrder
     * @module Sales
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportBatchTransfer
     */
    public function getMerchantOrderExpenseData(
        DataExportConfigurationTransfer $dataExportConfigurationTransfer
    ): DataExportBatchTransfer;
}
