<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\MerchantSalesOrder\Dependency\Facade\MerchantSalesOrderToCalculationFacadeInterface;
use Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface;

class MerchantOrderTotalsCreator implements MerchantOrderTotalsCreatorInterface
{
    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface
     */
    protected $merchantSalesOrderEntityManager;

    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Dependency\Facade\MerchantSalesOrderToCalculationFacadeInterface
     */
    protected $calculationFacade;

    /**
     * @param \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface $merchantSalesOrderEntityManager
     * @param \Spryker\Zed\MerchantSalesOrder\Dependency\Facade\MerchantSalesOrderToCalculationFacadeInterface $calculationFacade
     */
    public function __construct(
        MerchantSalesOrderEntityManagerInterface $merchantSalesOrderEntityManager,
        MerchantSalesOrderToCalculationFacadeInterface $calculationFacade
    ) {
        $this->merchantSalesOrderEntityManager = $merchantSalesOrderEntityManager;
        $this->calculationFacade = $calculationFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    public function createMerchantOrderTotals(
        OrderTransfer $orderTransfer,
        MerchantOrderTransfer $merchantOrderTransfer
    ): TotalsTransfer {
        $totalsTransfer = $this->calculateTotals($orderTransfer, $merchantOrderTransfer);

        return $this->merchantSalesOrderEntityManager
            ->createMerchantOrderTotals($merchantOrderTransfer->getIdMerchantOrder(), $totalsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    protected function calculateTotals(
        OrderTransfer $orderTransfer,
        MerchantOrderTransfer $merchantOrderTransfer
    ): TotalsTransfer {
        $calculationOrderTransfer = (new OrderTransfer())
            ->setPriceMode($orderTransfer->getPriceMode())
            ->setTotals(new TotalsTransfer());
        $calculationOrderTransfer = $this->expandOrderWithMerchantExpenses(
            $calculationOrderTransfer,
            $merchantOrderTransfer,
            $orderTransfer->getExpenses()
        );
        $calculationOrderTransfer = $this->expandOrderWithMerchantOrderItems(
            $calculationOrderTransfer,
            $merchantOrderTransfer
        );

        $calculationOrderTransfer = $this->calculationFacade->recalculateOrder($calculationOrderTransfer);

        return $calculationOrderTransfer->getTotals();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $calculationOrderTransfer
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     * @param \ArrayObject $expenseTransfers
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function expandOrderWithMerchantExpenses(
        OrderTransfer $calculationOrderTransfer,
        MerchantOrderTransfer $merchantOrderTransfer,
        ArrayObject $expenseTransfers
    ): OrderTransfer {
        foreach ($expenseTransfers as $expenseTransfer) {
            if ($expenseTransfer->getMerchantReference() !== $merchantOrderTransfer->getMerchantReference()) {
                continue;
            }

            $calculationOrderTransfer->addExpense($expenseTransfer);
        }

        return $calculationOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $calculationOrderTransfer
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function expandOrderWithMerchantOrderItems(
        OrderTransfer $calculationOrderTransfer,
        MerchantOrderTransfer $merchantOrderTransfer
    ): OrderTransfer {
        foreach ($merchantOrderTransfer->getMerchantOrderItems() as $merchantOrderItemTransfer) {
            $calculationOrderTransfer->addItem($merchantOrderItemTransfer->getOrderItem());
        }

        return $calculationOrderTransfer;
    }
}
