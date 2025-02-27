<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesPayment\Business\Calculator\CaptureAmountCalculator;
use Spryker\Zed\SalesPayment\Business\Calculator\CaptureAmountCalculatorInterface;
use Spryker\Zed\SalesPayment\Business\Calculator\RefundAmountCalculator;
use Spryker\Zed\SalesPayment\Business\Calculator\RefundAmountCalculatorInterface;
use Spryker\Zed\SalesPayment\Business\Deleter\SalesPaymentDeleter;
use Spryker\Zed\SalesPayment\Business\Deleter\SalesPaymentDeleterInterface;
use Spryker\Zed\SalesPayment\Business\Expander\SalesOrderExpander;
use Spryker\Zed\SalesPayment\Business\Expander\SalesOrderExpanderInterface;
use Spryker\Zed\SalesPayment\Business\MessageEmitter\MessageEmitter;
use Spryker\Zed\SalesPayment\Business\MessageEmitter\MessageEmitterInterface;
use Spryker\Zed\SalesPayment\Business\Reader\SalesPaymentReader;
use Spryker\Zed\SalesPayment\Business\Reader\SalesPaymentReaderInterface;
use Spryker\Zed\SalesPayment\Business\Replacer\SalesPaymentReplacer;
use Spryker\Zed\SalesPayment\Business\Replacer\SalesPaymentReplacerInterface;
use Spryker\Zed\SalesPayment\Business\Writer\SalesPaymentWriter;
use Spryker\Zed\SalesPayment\Business\Writer\SalesPaymentWriterInterface;
use Spryker\Zed\SalesPayment\Dependency\Facade\SalesPaymentToMessageBrokerFacadeInterface;
use Spryker\Zed\SalesPayment\Dependency\Facade\SalesPaymentToSalesFacadeInterface;
use Spryker\Zed\SalesPayment\SalesPaymentDependencyProvider;

/**
 * @method \Spryker\Zed\SalesPayment\SalesPaymentConfig getConfig()
 * @method \Spryker\Zed\SalesPayment\Persistence\SalesPaymentEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesPayment\Persistence\SalesPaymentRepositoryInterface getRepository()
 */
class SalesPaymentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesPayment\Business\Expander\SalesOrderExpanderInterface
     */
    public function createSalesOrderExpander(): SalesOrderExpanderInterface
    {
        return new SalesOrderExpander(
            $this->getRepository(),
            $this->getOrderPaymentExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesPayment\Business\Writer\SalesPaymentWriterInterface
     */
    public function createSalesPaymentWriter(): SalesPaymentWriterInterface
    {
        return new SalesPaymentWriter(
            $this->getEntityManager(),
            $this->getPaymentMapKeyBuilderStrategyPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesPayment\Business\MessageEmitter\MessageEmitterInterface
     */
    public function createMessageEmitter(): MessageEmitterInterface
    {
        return new MessageEmitter(
            $this->getMessageBrokerFacade(),
            $this->getSalesFacade(),
            $this->getConfig(),
            $this->createCaptureAmountCalculator(),
            $this->createRefundAmountCalculator(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesPayment\Business\Calculator\CaptureAmountCalculatorInterface
     */
    public function createCaptureAmountCalculator(): CaptureAmountCalculatorInterface
    {
        return new CaptureAmountCalculator($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\SalesPayment\Business\Calculator\RefundAmountCalculatorInterface
     */
    public function createRefundAmountCalculator(): RefundAmountCalculatorInterface
    {
        return new RefundAmountCalculator($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\SalesPayment\Business\Replacer\SalesPaymentReplacerInterface
     */
    public function createSalesPaymentReplacer(): SalesPaymentReplacerInterface
    {
        return new SalesPaymentReplacer(
            $this->createSalesPaymentReader(),
            $this->createSalesPaymentWriter(),
            $this->createSalesPaymentDeleter(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesPayment\Business\Deleter\SalesPaymentDeleterInterface
     */
    public function createSalesPaymentDeleter(): SalesPaymentDeleterInterface
    {
        return new SalesPaymentDeleter(
            $this->getEntityManager(),
            $this->getSalesPaymentPreDeletePlugins(),
        );
    }

    /**
     * @return array<\Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\OrderPaymentExpanderPluginInterface>
     */
    public function getOrderPaymentExpanderPlugins(): array
    {
        return $this->getProvidedDependency(SalesPaymentDependencyProvider::SALES_PAYMENT_EXPANDER_PLUGINS);
    }

    /**
     * @return array<\Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\PaymentMapKeyBuilderStrategyPluginInterface>
     */
    public function getPaymentMapKeyBuilderStrategyPlugins(): array
    {
        return $this->getProvidedDependency(SalesPaymentDependencyProvider::PAYMENT_MAP_KEY_BUILDER_STRATEGY_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\SalesPayment\Dependency\Facade\SalesPaymentToMessageBrokerFacadeInterface
     */
    public function getMessageBrokerFacade(): SalesPaymentToMessageBrokerFacadeInterface
    {
        return $this->getProvidedDependency(SalesPaymentDependencyProvider::FACADE_MESSAGE_BROKER);
    }

    /**
     * @return \Spryker\Zed\SalesPayment\Dependency\Facade\SalesPaymentToSalesFacadeInterface
     */
    public function getSalesFacade(): SalesPaymentToSalesFacadeInterface
    {
        return $this->getProvidedDependency(SalesPaymentDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\SalesPayment\Business\Reader\SalesPaymentReaderInterface
     */
    public function createSalesPaymentReader(): SalesPaymentReaderInterface
    {
        return new SalesPaymentReader(
            $this->getRepository(),
        );
    }

    /**
     * @return list<\Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\SalesPaymentPreDeletePluginInterface>
     */
    public function getSalesPaymentPreDeletePlugins(): array
    {
        return $this->getProvidedDependency(SalesPaymentDependencyProvider::PLUGINS_SALES_PAYMENT_PRE_DELETE);
    }
}
