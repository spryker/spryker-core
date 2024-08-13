<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Communication\Reader;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesPaymentMerchant\Communication\Exception\OrderNotFoundException;
use Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToSalesFacadeInterface;

class SalesOrderReader implements SalesOrderReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToSalesFacadeInterface
     */
    protected SalesPaymentMerchantToSalesFacadeInterface $salesFacade;

    /**
     * @param \Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToSalesFacadeInterface $salesFacade
     */
    public function __construct(SalesPaymentMerchantToSalesFacadeInterface $salesFacade)
    {
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param int $idSalesOrder
     *
     * @throws \Spryker\Zed\SalesPaymentMerchant\Communication\Exception\OrderNotFoundException
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderTransfer(int $idSalesOrder): OrderTransfer
    {
        $orderTransfer = $this->salesFacade->findOrderByIdSalesOrder($idSalesOrder);
        if (!$orderTransfer) {
            throw new OrderNotFoundException();
        }

        return $orderTransfer;
    }
}
