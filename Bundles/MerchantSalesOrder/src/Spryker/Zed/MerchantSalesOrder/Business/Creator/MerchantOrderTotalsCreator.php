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
        $total = rand(1000, 9999);
        $totalsTransfer = (new TotalsTransfer())
            ->setIdMerchantOrder($merchantOrderTransfer->getIdMerchantOrder())
            ->setRefundTotal(0)
            ->setGrandTotal($total)
            ->setTaxTotal((new TaxTotalTransfer()))
            ->setExpenseTotal(0)
            ->setSubtotal($total)
            ->setDiscountTotal(0)
            ->setCanceledTotal(0);

        return $this->merchantSalesOrderEntityManager->createMerchantOrderTotals($totalsTransfer);
    }
}
