<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderDataExport\Business\Reader;

use Generated\Shared\Transfer\DataExportBatchTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Spryker\Zed\MerchantSalesOrderDataExport\Persistence\MerchantSalesOrderDataExportRepositoryInterface;

class MerchantOrderItemDataReader implements MerchantSalesOrderDataReaderInterface
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
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\DataExportBatchTransfer
     */
    public function readBatch(DataExportConfigurationTransfer $dataExportConfigurationTransfer, int $offset, int $limit): DataExportBatchTransfer
    {
        return $this->merchantSalesOrderDataExportRepository->getMerchantOrderItemData($dataExportConfigurationTransfer, $offset, $limit);
    }
}
