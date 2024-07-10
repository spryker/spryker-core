<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionDataExport\Business;

use Generated\Shared\Transfer\MerchantCommissionExportRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionExportResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantCommissionDataExport\Business\MerchantCommissionDataExportBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantCommissionDataExport\Persistence\MerchantCommissionDataExportRepositoryInterface getRepository()
 */
class MerchantCommissionDataExportFacade extends AbstractFacade implements MerchantCommissionDataExportFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionExportRequestTransfer $merchantCommissionExportRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionExportResponseTransfer
     */
    public function exportMerchantCommissionsByMerchantCommissionExportRequest(
        MerchantCommissionExportRequestTransfer $merchantCommissionExportRequestTransfer
    ): MerchantCommissionExportResponseTransfer {
        return $this->getFactory()
            ->createMerchantCommissionDataExporter()
            ->exportByMerchantCommissionExportRequest($merchantCommissionExportRequestTransfer);
    }
}
