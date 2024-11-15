<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Processor\OrderAmendmentProcessor;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Processor\OrderAmendmentProcessorInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Reader\OrderReader;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Reader\OrderReaderInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Triggerer\OmsEventTriggerer;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Triggerer\OmsEventTriggererInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Validator\QuoteValidator;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Validator\QuoteValidatorInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Validator\SalesOrderAmendmentValidator;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Validator\SalesOrderAmendmentValidatorInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToOmsFacadeInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesFacadeInterface;
use Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsDependencyProvider;

/**
 * @method \Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig getConfig()
 */
class SalesOrderAmendmentOmsBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesOrderAmendmentOms\Business\Validator\QuoteValidatorInterface
     */
    public function createQuoteValidator(): QuoteValidatorInterface
    {
        return new QuoteValidator(
            $this->getConfig(),
            $this->getOmsFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendmentOms\Business\Processor\OrderAmendmentProcessorInterface
     */
    public function createOrderAmendmentProcessor(): OrderAmendmentProcessorInterface
    {
        return new OrderAmendmentProcessor(
            $this->createOmsEventTriggerer(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendmentOms\Business\Triggerer\OmsEventTriggererInterface
     */
    public function createOmsEventTriggerer(): OmsEventTriggererInterface
    {
        return new OmsEventTriggerer(
            $this->createOrderReader(),
            $this->getConfig(),
            $this->getOmsFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendmentOms\Business\Validator\SalesOrderAmendmentValidatorInterface
     */
    public function createSalesOrderAmendmentValidator(): SalesOrderAmendmentValidatorInterface
    {
        return new SalesOrderAmendmentValidator(
            $this->createOrderReader(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendmentOms\Business\Reader\OrderReaderInterface
     */
    public function createOrderReader(): OrderReaderInterface
    {
        return new OrderReader($this->getSalesFacade());
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToOmsFacadeInterface
     */
    public function getOmsFacade(): SalesOrderAmendmentOmsToOmsFacadeInterface
    {
        return $this->getProvidedDependency(SalesOrderAmendmentOmsDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesFacadeInterface
     */
    public function getSalesFacade(): SalesOrderAmendmentOmsToSalesFacadeInterface
    {
        return $this->getProvidedDependency(SalesOrderAmendmentOmsDependencyProvider::FACADE_SALES);
    }
}
