<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionDataExport\Business;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportReportTransfer;
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
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     */
    public function exportMerchantCommission(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportReportTransfer
    {
        return $this->getFactory()
            ->createMerchantCommissionDataExporter()
            ->export($dataExportConfigurationTransfer);
    }
}
