<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionGuiExtension\Communication\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantCommissionExportRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionExportResponseTransfer;

/**
 * Implement this plugin interface to be able to export merchant commissions via the GUI interface.
 */
interface MerchantCommissionExportPluginInterface
{
    /**
     * Specification:
     * - Exports merchant commissions according to provided `MerchantCommissionExportRequestTransfer`.
     * - Returns `MerchantCommissionExportResponseTransfer` with errors if any occurs.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionExportRequestTransfer $merchantCommissionExportRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionExportResponseTransfer
     */
    public function exportMerchantCommissions(
        MerchantCommissionExportRequestTransfer $merchantCommissionExportRequestTransfer
    ): MerchantCommissionExportResponseTransfer;
}
