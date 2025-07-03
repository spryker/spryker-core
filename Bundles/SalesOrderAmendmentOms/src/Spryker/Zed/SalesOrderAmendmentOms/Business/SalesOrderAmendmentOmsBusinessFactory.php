<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Expander\OrderExpander;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Expander\OrderExpanderInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Processor\OrderAmendmentProcessor;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Processor\OrderAmendmentProcessorInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Reader\OmsOrderItemStateReader;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Reader\OmsOrderItemStateReaderInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Reader\OrderReader;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Reader\OrderReaderInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Triggerer\OmsEventTriggerer;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Triggerer\OmsEventTriggererInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Updater\ItemReservationUpdater;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Updater\ItemReservationUpdaterInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Validator\CartReorderRequestValidator;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Validator\CartReorderRequestValidatorInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Validator\OrderValidator;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Validator\OrderValidatorInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Validator\QuoteValidator;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Validator\QuoteValidatorInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Validator\SalesOrderAmendmentValidator;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Validator\SalesOrderAmendmentValidatorInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToOmsFacadeInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesFacadeInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Service\SalesOrderAmendmentOmsToSalesOrderAmendmentServiceInterface;
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
        return new QuoteValidator($this->createOrderValidator());
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendmentOms\Business\Validator\OrderValidatorInterface
     */
    public function createOrderValidator(): OrderValidatorInterface
    {
        return new OrderValidator(
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
     * @return \Spryker\Zed\SalesOrderAmendmentOms\Business\Validator\CartReorderRequestValidatorInterface
     */
    public function createCartReorderRequestValidator(): CartReorderRequestValidatorInterface
    {
        return new CartReorderRequestValidator($this->createOrderValidator());
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendmentOms\Business\Reader\OrderReaderInterface
     */
    public function createOrderReader(): OrderReaderInterface
    {
        return new OrderReader($this->getSalesFacade());
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendmentOms\Business\Expander\OrderExpanderInterface
     */
    public function createOrderExpander(): OrderExpanderInterface
    {
        return new OrderExpander($this->createOrderValidator());
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendmentOms\Business\Reader\OmsOrderItemStateReaderInterface
     */
    public function createOmsOrderItemStateReader(): OmsOrderItemStateReaderInterface
    {
        return new OmsOrderItemStateReader(
            $this->getOmsFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendmentOms\Business\Updater\ItemReservationUpdaterInterface
     */
    public function createItemReservationUpdater(): ItemReservationUpdaterInterface
    {
        return new ItemReservationUpdater(
            $this->getOmsFacade(),
            $this->getSalesOrderAmendmentFacade(),
            $this->getSalesOrderAmendmentService(),
        );
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

    /**
     * @return \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface
     */
    public function getSalesOrderAmendmentFacade(): SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface
    {
        return $this->getProvidedDependency(SalesOrderAmendmentOmsDependencyProvider::FACADE_SALES_ORDER_AMENDMENT);
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Service\SalesOrderAmendmentOmsToSalesOrderAmendmentServiceInterface
     */
    public function getSalesOrderAmendmentService(): SalesOrderAmendmentOmsToSalesOrderAmendmentServiceInterface
    {
        return $this->getProvidedDependency(SalesOrderAmendmentOmsDependencyProvider::SERVICE_SALES_ORDER_AMENDMENT);
    }
}
