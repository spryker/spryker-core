<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderDataExport\Business;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportReportTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantSalesOrderDataExport\Business\MerchantSalesOrderDataExportBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantSalesOrderDataExport\MerchantSalesOrderDataExportConfig getConfig()
 * @method \Spryker\Zed\MerchantSalesOrderDataExport\Persistence\MerchantSalesOrderDataExportRepositoryInterface getRepository()
 */
class MerchantSalesOrderDataExportFacade extends AbstractFacade implements MerchantSalesOrderDataExportFacadeInterface
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
    public function exportMerchantOrder(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportReportTransfer
    {
        return $this->getFactory()
            ->createMerchantOrderExporter()
            ->export($dataExportConfigurationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     */
    public function exportMerchantOrderItem(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportReportTransfer
    {
        return $this->getFactory()
            ->createMerchantOrderItemExporter()
            ->export($dataExportConfigurationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     */
    public function exportMerchantOrderExpense(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportReportTransfer
    {
        return $this->getFactory()
            ->createMerchantOrderExpenseExporter()
            ->export($dataExportConfigurationTransfer);
    }
}
