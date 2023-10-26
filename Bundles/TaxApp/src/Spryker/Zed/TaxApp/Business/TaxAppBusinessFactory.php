<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Business;

use Spryker\Client\TaxApp\TaxAppClientInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\TaxApp\Business\AccessTokenProvider\AccessTokenProvider;
use Spryker\Zed\TaxApp\Business\AccessTokenProvider\AccessTokenProviderInterface;
use Spryker\Zed\TaxApp\Business\Aggregator\PriceAggregator;
use Spryker\Zed\TaxApp\Business\Aggregator\PriceAggregatorInterface;
use Spryker\Zed\TaxApp\Business\Calculator\Calculator;
use Spryker\Zed\TaxApp\Business\Calculator\CalculatorInterface;
use Spryker\Zed\TaxApp\Business\Config\ConfigDeleter;
use Spryker\Zed\TaxApp\Business\Config\ConfigDeleterInterface;
use Spryker\Zed\TaxApp\Business\Config\ConfigWriter;
use Spryker\Zed\TaxApp\Business\Config\ConfigWriterInterface;
use Spryker\Zed\TaxApp\Business\Mapper\Addresses\AddressMapper;
use Spryker\Zed\TaxApp\Business\Mapper\Addresses\AddressMapperInterface;
use Spryker\Zed\TaxApp\Business\Mapper\Prices\ItemExpenseItemExpensePriceRetriever;
use Spryker\Zed\TaxApp\Business\Mapper\Prices\ItemExpensePriceRetrieverInterface;
use Spryker\Zed\TaxApp\Business\Mapper\TaxAppMapper;
use Spryker\Zed\TaxApp\Business\Mapper\TaxAppMapperInterface;
use Spryker\Zed\TaxApp\Business\PaymentSubmitTaxInvoiceSender\PaymentSubmitTaxInvoiceSender;
use Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToMessageBrokerFacadeInterface;
use Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToOauthClientFacadeInterface;
use Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToSalesFacadeInterface;
use Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeInterface;
use Spryker\Zed\TaxApp\TaxAppDependencyProvider;

/**
 * @method \Spryker\Zed\TaxApp\Persistence\TaxAppEntityManagerInterface getEntityManager()()
 * @method \Spryker\Zed\TaxApp\Persistence\TaxAppRepositoryInterface getRepository()
 * @method \Spryker\Zed\TaxApp\TaxAppConfig getConfig()
 */
class TaxAppBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\TaxApp\Business\Config\ConfigWriterInterface
     */
    public function createConfigWriter(): ConfigWriterInterface
    {
        return new ConfigWriter($this->getEntityManager(), $this->getStoreFacade());
    }

    /**
     * @return \Spryker\Zed\TaxApp\Business\Config\ConfigDeleterInterface
     */
    public function createConfigDeleter(): ConfigDeleterInterface
    {
        return new ConfigDeleter($this->getEntityManager(), $this->getStoreFacade());
    }

    /**
     * @return \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeInterface
     */
    public function getStoreFacade(): TaxAppToStoreFacadeInterface
    {
        return $this->getProvidedDependency(TaxAppDependencyProvider::FACADE_STORE);
    }

    /**
     * @return array<\Spryker\Zed\TaxAppExtension\Dependency\Plugin\CalculableObjectTaxAppExpanderPluginInterface>
     */
    public function getCalculableObjectTaxAppExpanderPlugins(): array
    {
        return $this->getProvidedDependency(TaxAppDependencyProvider::PLUGINS_CALCULABLE_OBJECT_TAX_APP_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\TaxAppExtension\Dependency\Plugin\OrderTaxAppExpanderPluginInterface>
     */
    public function getOrderTaxAppExpanderPlugins(): array
    {
        return $this->getProvidedDependency(TaxAppDependencyProvider::PLUGINS_ORDER_TAX_APP_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\TaxApp\Business\Calculator\CalculatorInterface
     */
    public function createCalculator(): CalculatorInterface
    {
        return new Calculator(
            $this->createTaxAppMapper(),
            $this->getTaxAppClient(),
            $this->getStoreFacade(),
            $this->getRepository(),
            $this->createAccessTokenProvider(),
            $this->getCalculableObjectTaxAppExpanderPlugins(),
            $this->createPriceAggregator(),
        );
    }

    /**
     * @return \Spryker\Zed\TaxApp\Business\AccessTokenProvider\AccessTokenProviderInterface
     */
    public function createAccessTokenProvider(): AccessTokenProviderInterface
    {
        return new AccessTokenProvider(
            $this->getOauthClientFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\TaxApp\Business\PaymentSubmitTaxInvoiceSender\PaymentSubmitTaxInvoiceSender
     */
    public function createPaymentSubmitTaxInvoiceSender(): PaymentSubmitTaxInvoiceSender
    {
        return new PaymentSubmitTaxInvoiceSender(
            $this->getMessageBrokerFacade(),
            $this->getStoreFacade(),
            $this->getSalesFacade(),
            $this->createTaxAppMapper(),
            $this->getOrderTaxAppExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToMessageBrokerFacadeInterface
     */
    public function getMessageBrokerFacade(): TaxAppToMessageBrokerFacadeInterface
    {
        return $this->getProvidedDependency(TaxAppDependencyProvider::FACADE_MESSAGE_BROKER);
    }

    /**
     * @return \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToSalesFacadeInterface
     */
    public function getSalesFacade(): TaxAppToSalesFacadeInterface
    {
        return $this->getProvidedDependency(TaxAppDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\TaxApp\Business\Mapper\TaxAppMapperInterface
     */
    public function createTaxAppMapper(): TaxAppMapperInterface
    {
        return new TaxAppMapper($this->createAddressMapper(), $this->createItemExpensePriceRetriever());
    }

    /**
     * @return \Spryker\Zed\TaxApp\Business\Mapper\Addresses\AddressMapperInterface
     */
    public function createAddressMapper(): AddressMapperInterface
    {
        return new AddressMapper();
    }

    /**
     * @return \Spryker\Zed\TaxApp\Business\Mapper\Prices\ItemExpensePriceRetrieverInterface
     */
    public function createItemExpensePriceRetriever(): ItemExpensePriceRetrieverInterface
    {
        return new ItemExpenseItemExpensePriceRetriever();
    }

    /**
     * @return \Spryker\Client\TaxApp\TaxAppClientInterface
     */
    public function getTaxAppClient(): TaxAppClientInterface
    {
        return $this->getProvidedDependency(TaxAppDependencyProvider::CLIENT_TAX_APP);
    }

    /**
     * @return \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToOauthClientFacadeInterface
     */
    public function getOauthClientFacade(): TaxAppToOauthClientFacadeInterface
    {
        return $this->getProvidedDependency(TaxAppDependencyProvider::FACADE_OAUTH_CLIENT);
    }

    /**
     * @return \Spryker\Zed\TaxApp\Business\Aggregator\PriceAggregatorInterface
     */
    public function createPriceAggregator(): PriceAggregatorInterface
    {
        return new PriceAggregator();
    }
}
