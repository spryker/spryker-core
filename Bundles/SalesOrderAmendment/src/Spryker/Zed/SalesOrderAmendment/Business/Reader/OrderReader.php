<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Reader;

use Generated\Shared\Transfer\OrderListRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesOrderAmendment\Dependency\Facade\SalesOrderAmendmentToSalesFacadeInterface;

class OrderReader implements OrderReaderInterface
{
    /**
     * @param \Spryker\Zed\SalesOrderAmendment\Dependency\Facade\SalesOrderAmendmentToSalesFacadeInterface $salesFacade
     */
    public function __construct(protected SalesOrderAmendmentToSalesFacadeInterface $salesFacade)
    {
    }

    /**
     * @param string $orderReference
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function findCustomerOrder(string $orderReference, string $customerReference): ?OrderTransfer
    {
        $orderListRequestTransfer = (new OrderListRequestTransfer())
            ->addOrderReference($orderReference)
            ->setCustomerReference($customerReference);

        return $this->salesFacade
            ->getOffsetPaginatedCustomerOrderList($orderListRequestTransfer)
            ->getOrders()
            ->getIterator()
            ->current();
    }
}
