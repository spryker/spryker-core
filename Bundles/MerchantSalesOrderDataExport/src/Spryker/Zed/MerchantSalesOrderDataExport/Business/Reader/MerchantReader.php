<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderDataExport\Business\Reader;

use Spryker\Zed\MerchantSalesOrderDataExport\Persistence\MerchantSalesOrderDataExportRepositoryInterface;

class MerchantReader implements MerchantReaderInterface
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
     * @return string[]
     */
    public function readMerchantNames(): array
    {
        return $this->merchantSalesOrderDataExportRepository->getMerchantNames();
    }
}
