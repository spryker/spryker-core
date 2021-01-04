<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderDataExport\Business\Reader;

use Generated\Shared\Transfer\DataExportBatchTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Spryker\Zed\MerchantSalesOrderDataExport\Persistence\MerchantSalesOrderDataExportRepositoryInterface;

class MerchantOrderExpenseDataReader implements DataReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantSalesOrderDataExport\Persistence\MerchantSalesOrderDataExportRepositoryInterface
     */
    protected $merchantSalesOrderDataExportRepository;

    /**
     * @param \Spryker\Zed\MerchantSalesOrderDataExport\Persistence\MerchantSalesOrderDataExportRepositoryInterface $merchantSalesOrderDataExportRepository
     */
    public function __construct(MerchantSalesOrderDataExportRepositoryInterface $merchantSalesOrderDataExportRepository)
    {
        $this->merchantSalesOrderDataExportRepository = $merchantSalesOrderDataExportRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportBatchTransfer
     */
    public function readBatch(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportBatchTransfer
    {
        return $this->merchantSalesOrderDataExportRepository->getMerchantOrderExpenseData($dataExportConfigurationTransfer);
    }
}
