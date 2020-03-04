<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\Creator;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface;

class MerchantOrderTotalsCreator implements MerchantOrderTotalsCreatorInterface
{
    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface
     */
    protected $merchantSalesOrderEntityManager;

    /**
     * @param \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface $merchantSalesOrderEntityManager
     */
    public function __construct(MerchantSalesOrderEntityManagerInterface $merchantSalesOrderEntityManager)
    {
        $this->merchantSalesOrderEntityManager = $merchantSalesOrderEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    public function createMerchantOrderTotals(MerchantOrderTransfer $merchantOrderTransfer): TotalsTransfer
    {
        $totalsTransfer = (new TotalsTransfer())
            ->setRefundTotal(0)
            ->setGrandTotal(0)
            ->setTaxTotal((new TaxTotalTransfer()))
            ->setExpenseTotal(0)
            ->setSubtotal(0)
            ->setDiscountTotal(0)
            ->setCanceledTotal(0);

        return $this->merchantSalesOrderEntityManager
            ->createMerchantOrderTotals($merchantOrderTransfer->getIdMerchantOrder(), $totalsTransfer);
    }
}
