<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business\Reader;

use Generated\Shared\Transfer\OrderConditionsTransfer;
use Generated\Shared\Transfer\OrderCriteriaTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesFacadeInterface;

class OrderReader implements OrderReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesFacadeInterface
     */
    protected SalesOrderAmendmentOmsToSalesFacadeInterface $salesFacade;

    /**
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesFacadeInterface $salesFacade
     */
    public function __construct(SalesOrderAmendmentOmsToSalesFacadeInterface $salesFacade)
    {
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param string $orderReference
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function findOrderByOrderReference(string $orderReference): ?OrderTransfer
    {
        $orderConditionsTransfer = (new OrderConditionsTransfer())
            ->addOrderReference($orderReference);
        $orderCriteriaTransfer = (new OrderCriteriaTransfer())->setOrderConditions($orderConditionsTransfer);

        return $this->salesFacade
            ->getOrderCollection($orderCriteriaTransfer)
            ->getOrders()
            ->getIterator()
            ->current();
    }
}
