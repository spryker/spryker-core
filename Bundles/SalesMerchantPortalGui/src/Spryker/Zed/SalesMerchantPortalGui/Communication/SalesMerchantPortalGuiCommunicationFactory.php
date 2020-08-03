<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Communication;

use Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SalesMerchantPortalGui\Communication\ConfigurationProvider\MerchantOrderGuiTableConfigurationProvider;
use Spryker\Zed\SalesMerchantPortalGui\Communication\ConfigurationProvider\MerchantOrderGuiTableConfigurationProviderInterface;
use Spryker\Zed\SalesMerchantPortalGui\Communication\DataProvider\OrdersDashboardCardProvider;
use Spryker\Zed\SalesMerchantPortalGui\Communication\DataProvider\OrdersDashboardCardProviderInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToCurrencyFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantOmsFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantSalesOrderFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMoneyFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToRouterFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToSalesFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToStoreFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\SalesMerchantPortalGuiDependencyProvider;
use Twig\Environment;

/**
 * @method \Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesMerchantPortalGui\SalesMerchantPortalGuiConfig getConfig()
 */
class SalesMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\SalesMerchantPortalGui\Communication\ConfigurationProvider\MerchantOrderGuiTableConfigurationProviderInterface
     */
    public function createMerchantOrderGuiTableConfigurationProvider(): MerchantOrderGuiTableConfigurationProviderInterface
    {
        return new MerchantOrderGuiTableConfigurationProvider(
            $this->getStoreFacade(),
            $this->getMerchantOmsFacade(),
            $this->getMerchantUserFacade(),
            $this->getGuiTableFactory()
        );
    }

    /**
     * @return \Spryker\Zed\SalesMerchantPortalGui\Communication\ConfigurationProvider\MerchantOrderItemGuiTableConfigurationProviderInterface
     */
    public function createMerchantOrderItemGuiTableConfigurationProvider(): MerchantOrderItemGuiTableConfigurationProviderInterface
    {
        return new MerchantOrderItemGuiTableConfigurationProvider(
            $this->getMerchantOmsFacade(),
            $this->getMerchantUserFacade(),
            $this->getMerchantOrderItemTableExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface
     */
    public function createMerchantOrderGuiTableDataProvider(): GuiTableDataProviderInterface
    {
        return new MerchantOrderGuiTableDataProvider(
            $this->getRepository(),
            $this->getMerchantUserFacade(),
            $this->getCurrencyFacade(),
            $this->getMoneyFacade()
        );
    }

    /**
     * @return \Spryker\Zed\GuiTable\Communication\DataProvider\GuiTableDataProviderInterface
     */
    public function createMerchantOrderItemGuiTableDataProvider(): GuiTableDataProviderInterface
    {
        return new MerchantOrderItemGuiTableDataProvider(
            $this->getRepository(),
            $this->getMerchantUserFacade(),
            $this->getMerchantOmsFacade(),
            $this->getSalesFacade(),
            $this->getMerchantOrderItemTableExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\SalesMerchantPortalGui\Communication\DataProvider\OrdersDashboardCardProviderInterface
     */
    public function createOrdersDashboardCardProvider(): OrdersDashboardCardProviderInterface
    {
        return new OrdersDashboardCardProvider(
            $this->getRepository(),
            $this->getMerchantUserFacade(),
            $this->getRouterFacade(),
            $this->getConfig(),
            $this->getTwigEnvironment()
        );
    }

    /**
     * @return \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): SalesMerchantPortalGuiToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(SalesMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToCurrencyFacadeInterface
     */
    public function getCurrencyFacade(): SalesMerchantPortalGuiToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(SalesMerchantPortalGuiDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMoneyFacadeInterface
     */
    public function getMoneyFacade(): SalesMerchantPortalGuiToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(SalesMerchantPortalGuiDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToStoreFacadeInterface
     */
    public function getStoreFacade(): SalesMerchantPortalGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(SalesMerchantPortalGuiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantOmsFacadeInterface
     */
    public function getMerchantOmsFacade(): SalesMerchantPortalGuiToMerchantOmsFacadeInterface
    {
        return $this->getProvidedDependency(SalesMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_OMS);
    }

    /**
     * @return \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantSalesOrderFacadeInterface
     */
    public function getMerchantSalesOrderFacade(): SalesMerchantPortalGuiToMerchantSalesOrderFacadeInterface
    {
        return $this->getProvidedDependency(SalesMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_SALES_ORDER);
    }

    /**
     * @return \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToRouterFacadeInterface
     */
    public function getRouterFacade(): SalesMerchantPortalGuiToRouterFacadeInterface
    {
        return $this->getProvidedDependency(SalesMerchantPortalGuiDependencyProvider::FACADE_ROUTER);
    }

    /**
     * @return \Twig\Environment
     */
    public function getTwigEnvironment(): Environment
    {
        return $this->getProvidedDependency(SalesMerchantPortalGuiDependencyProvider::SERVICE_TWIG);
    }

    /**
     * @return \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToSalesFacadeInterface
     */
    public function getSalesFacade(): SalesMerchantPortalGuiToSalesFacadeInterface
    {
        return $this->getProvidedDependency(SalesMerchantPortalGuiDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): SalesMerchantPortalGuiToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(SalesMerchantPortalGuiDependencyProvider::FACADE_TRANSLATOR);
    }

    /**
     * @return \Spryker\Zed\SalesMerchantPortalGuiExtension\Dependency\Plugin\MerchantOrderItemTableExpanderPluginInterface[]
     */
    public function getMerchantOrderItemTableExpanderPlugins(): array
    {
        return $this->getProvidedDependency(SalesMerchantPortalGuiDependencyProvider::PLUGINS_MERCHANT_ORDER_ITEM_TABLE_EXPANDER);
    }

    /**
     * @return \Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface
     */
    public function getGuiTableHttpDataRequestExecutor(): GuiTableDataRequestExecutorInterface
    {
        return $this->getProvidedDependency(SalesMerchantPortalGuiDependencyProvider::SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR);
    }

    /**
     * @return \\Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    public function getGuiTableFactory(): GuiTableFactoryInterface
    {
        return $this->getProvidedDependency(SalesMerchantPortalGuiDependencyProvider::SERVICE_GUI_TABLE_FACTORY);
    }
}
