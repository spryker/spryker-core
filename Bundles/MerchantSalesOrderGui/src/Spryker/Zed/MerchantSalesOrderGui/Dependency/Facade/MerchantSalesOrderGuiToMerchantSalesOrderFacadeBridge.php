<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade;

use ArrayObject;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery;
use Spryker\Zed\Sales\Business\SalesFacade;

class MerchantSalesOrderGuiToMerchantSalesOrderFacadeBridge implements MerchantSalesOrderGuiToMerchantSalesOrderFacadeInterface
{
    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderFacadeInterface
     */
    protected $merchantSalesOrderFacade;

    /**
     * @param \Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderFacadeInterface $merchantSalesOrderFacade
     */
    public function __construct($merchantSalesOrderFacade)
    {
        $this->merchantSalesOrderFacade = $merchantSalesOrderFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer|null
     */
    public function findMerchantOrder(MerchantOrderCriteriaTransfer $merchantCriteriaTransfer): ?MerchantOrderTransfer
    {
        $merchantOrderTransfer = $this->merchantSalesOrderFacade->findMerchantOrder($merchantCriteriaTransfer);
        $merchantOrderTransfer = $this->setOrder($merchantOrderTransfer);

        return $merchantOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer
     *
     * @return int
     */
    public function getMerchantOrdersCount(MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer): int
    {
        return SpyMerchantSalesOrderQuery::create()
            ->filterByMerchantReference($merchantOrderCriteriaTransfer->getMerchantReference())
            ->count();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    protected function setOrder(MerchantOrderTransfer $merchantOrderTransfer): MerchantOrderTransfer
    {
        $merchantOrderTransfer->setOrder(new OrderTransfer());
        $orderTransfer = (new SalesFacade())->findOrderByIdSalesOrder($merchantOrderTransfer->getIdOrder());

        if ($orderTransfer) {
            $merchantOrderTransfer->setOrder($orderTransfer);
            $merchantOrderTransfer->setExpenses($this->filterMerchantOrderExpenses($merchantOrderTransfer));
        }

        return $merchantOrderTransfer;
    }

    /**
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\ExpenseTransfer>
     *
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[]
     */
    protected function filterMerchantOrderExpenses(MerchantOrderTransfer $merchantOrderTransfer): ArrayObject
    {
        $expenseTransfers = new ArrayObject();

        foreach ($merchantOrderTransfer->getOrder()->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getMerchantReference() !== $merchantOrderTransfer->getMerchantReference()) {
                continue;
            }

            $expenseTransfers->append($expenseTransfer);
        }

        return $expenseTransfers;
    }
}
