<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderAmendmentExample\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\OrderAmendmentExample\Business\Processor\OrderAmendmentCheckoutProcessor;
use Spryker\Zed\OrderAmendmentExample\Business\Processor\OrderAmendmentCheckoutProcessorInterface;
use Spryker\Zed\OrderAmendmentExample\Business\Reader\SalesOrderAmendmentQuoteReader;
use Spryker\Zed\OrderAmendmentExample\Business\Reader\SalesOrderAmendmentQuoteReaderInterface;
use Spryker\Zed\OrderAmendmentExample\Dependency\Facade\OrderAmendmentExampleToCheckoutFacadeInterface;
use Spryker\Zed\OrderAmendmentExample\Dependency\Facade\OrderAmendmentExampleToSalesFacadeInterface;
use Spryker\Zed\OrderAmendmentExample\Dependency\Facade\OrderAmendmentExampleToSalesOrderAmendmentFacadeInterface;
use Spryker\Zed\OrderAmendmentExample\OrderAmendmentExampleDependencyProvider;

class OrderAmendmentExampleBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\OrderAmendmentExample\Business\Processor\OrderAmendmentCheckoutProcessorInterface
     */
    public function createOrderAmendmentCheckoutProcessor(): OrderAmendmentCheckoutProcessorInterface
    {
        return new OrderAmendmentCheckoutProcessor(
            $this->createSalesOrderAmendmentQuoteReader(),
            $this->getSalesOrderAmendmentFacade(),
            $this->getSalesFacade(),
            $this->getCheckoutFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\OrderAmendmentExample\Business\Reader\SalesOrderAmendmentQuoteReaderInterface
     */
    public function createSalesOrderAmendmentQuoteReader(): SalesOrderAmendmentQuoteReaderInterface
    {
        return new SalesOrderAmendmentQuoteReader($this->getSalesOrderAmendmentFacade());
    }

    /**
     * @return \Spryker\Zed\OrderAmendmentExample\Dependency\Facade\OrderAmendmentExampleToCheckoutFacadeInterface
     */
    public function getCheckoutFacade(): OrderAmendmentExampleToCheckoutFacadeInterface
    {
        return $this->getProvidedDependency(OrderAmendmentExampleDependencyProvider::FACADE_CHECKOUT);
    }

    /**
     * @return \Spryker\Zed\OrderAmendmentExample\Dependency\Facade\OrderAmendmentExampleToSalesOrderAmendmentFacadeInterface
     */
    public function getSalesOrderAmendmentFacade(): OrderAmendmentExampleToSalesOrderAmendmentFacadeInterface
    {
        return $this->getProvidedDependency(OrderAmendmentExampleDependencyProvider::FACADE_SALES_ORDER_AMENDMENT);
    }

    /**
     * @return \Spryker\Zed\OrderAmendmentExample\Dependency\Facade\OrderAmendmentExampleToSalesFacadeInterface
     */
    public function getSalesFacade(): OrderAmendmentExampleToSalesFacadeInterface
    {
        return $this->getProvidedDependency(OrderAmendmentExampleDependencyProvider::FACADE_SALES);
    }
}
