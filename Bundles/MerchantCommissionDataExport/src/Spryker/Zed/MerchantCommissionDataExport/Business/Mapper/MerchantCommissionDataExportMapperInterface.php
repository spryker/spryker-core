<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionDataExport\Business\Mapper;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportWriteResponseTransfer;
use Generated\Shared\Transfer\MerchantCommissionExportRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionExportResponseTransfer;

interface MerchantCommissionDataExportMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionExportRequestTransfer $merchantCommissionExportRequestTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    public function mapMerchantCommissionExportRequestTransferToDataExportConfigurationTransfer(
        MerchantCommissionExportRequestTransfer $merchantCommissionExportRequestTransfer,
        DataExportConfigurationTransfer $dataExportConfigurationTransfer
    ): DataExportConfigurationTransfer;

    /**
     * @param \Generated\Shared\Transfer\DataExportWriteResponseTransfer $dataExportWriteResponseTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionExportResponseTransfer $merchantCommissionExportResponseTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionExportResponseTransfer
     */
    public function mapDataExportWriteResponseTransferToMerchantCommissionExportResponseTransfer(
        DataExportWriteResponseTransfer $dataExportWriteResponseTransfer,
        MerchantCommissionExportResponseTransfer $merchantCommissionExportResponseTransfer
    ): MerchantCommissionExportResponseTransfer;
}
