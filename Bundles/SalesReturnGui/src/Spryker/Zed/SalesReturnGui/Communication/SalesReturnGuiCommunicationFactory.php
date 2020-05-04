<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SalesReturnGui\Communication\Table\OrderReturnTable;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToMoneyFacadeInterface;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToSalesReturnFacadeInterface;
use Spryker\Zed\SalesReturnGui\SalesReturnGuiDependencyProvider;

class SalesReturnGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Spryker\Zed\SalesReturnGui\Communication\Table\OrderReturnTable
     */
    public function createOrderReturnTable(OrderTransfer $orderTransfer): OrderReturnTable
    {
        return new OrderReturnTable(
            $orderTransfer,
            $this->getMoneyFacade()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToMoneyFacadeInterface
     */
    public function getMoneyFacade(): SalesReturnGuiToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(SalesReturnGuiDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToSalesReturnFacadeInterface
     */
    public function getSalesReturnFacade(): SalesReturnGuiToSalesReturnFacadeInterface
    {
        return $this->getProvidedDependency(SalesReturnGuiDependencyProvider::FACADE_SALES_RETURN);
    }
}
