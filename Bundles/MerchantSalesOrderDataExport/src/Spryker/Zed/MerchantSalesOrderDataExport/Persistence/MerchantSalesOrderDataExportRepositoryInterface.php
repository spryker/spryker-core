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
     * @module Country
     * @module Locale
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\DataExportBatchTransfer
     */
    public function getMerchantOrderData(DataExportConfigurationTransfer $dataExportConfigurationTransfer, int $offset, int $limit): DataExportBatchTransfer;

    /**
     * @module Country
     * @module Oms
     * @module Shipment
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\DataExportBatchTransfer
     */
    public function getMerchantOrderItemData(
        DataExportConfigurationTransfer $dataExportConfigurationTransfer,
        int $offset,
        int $limit
    ): DataExportBatchTransfer;

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\DataExportBatchTransfer
     */
    public function getMerchantOrderExpenseData(
        DataExportConfigurationTransfer $dataExportConfigurationTransfer,
        int $offset,
        int $limit
    ): DataExportBatchTransfer;
}
