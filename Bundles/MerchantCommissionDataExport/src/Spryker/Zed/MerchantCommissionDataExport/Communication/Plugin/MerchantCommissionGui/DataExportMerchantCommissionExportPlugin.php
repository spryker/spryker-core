<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionDataExport\Communication\Plugin\MerchantCommissionGui;

use Generated\Shared\Transfer\MerchantCommissionExportRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionExportResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantCommissionGuiExtension\Communication\Dependency\Plugin\MerchantCommissionExportPluginInterface;

/**
 * @method \Spryker\Zed\MerchantCommissionDataExport\MerchantCommissionDataExportConfig getConfig()
 * @method \Spryker\Zed\MerchantCommissionDataExport\Business\MerchantCommissionDataExportFacadeInterface getFacade()
 */
class DataExportMerchantCommissionExportPlugin extends AbstractPlugin implements MerchantCommissionExportPluginInterface
{
    /**
     * {@inheritDoc}
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
    public function exportMerchantCommissions(
        MerchantCommissionExportRequestTransfer $merchantCommissionExportRequestTransfer
    ): MerchantCommissionExportResponseTransfer {
        return $this->getFacade()
            ->exportMerchantCommissionsByMerchantCommissionExportRequest($merchantCommissionExportRequestTransfer);
    }
}
