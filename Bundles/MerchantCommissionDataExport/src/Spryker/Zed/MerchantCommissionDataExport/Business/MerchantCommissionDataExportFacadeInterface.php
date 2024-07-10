<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionDataExport\Business;

use Generated\Shared\Transfer\MerchantCommissionExportRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionExportResponseTransfer;

interface MerchantCommissionDataExportFacadeInterface
{
    /**
     * Specification:
     * - Requires `MerchantCommissionExportRequestTransfer.format` to be set.
     * - Requires `MerchantCommissionExportRequestTransfer.connection` to be set.
     * - Requires `MerchantCommissionExportRequestTransfer.destination` to be set.
     * - Expects `MerchantCommissionExportRequestTransfer.fields` to be not empty.
     * - Exports merchant commissions according to configuration provided in `MerchantCommissionExportRequestTransfer`.
     * - Returns `MerchantCommissionExportResponseTransfer` with errors if any occurs.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionExportRequestTransfer $merchantCommissionExportRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionExportResponseTransfer
     */
    public function exportMerchantCommissionsByMerchantCommissionExportRequest(
        MerchantCommissionExportRequestTransfer $merchantCommissionExportRequestTransfer
    ): MerchantCommissionExportResponseTransfer;
}
