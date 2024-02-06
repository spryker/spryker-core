<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentDashboardMerchantPortalGui\Communication;

use Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface;
use Spryker\Zed\AgentDashboardMerchantPortalGui\AgentDashboardMerchantPortalGuiDependencyProvider;
use Spryker\Zed\AgentDashboardMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\MerchantUserGuiTableConfigurationProvider;
use Spryker\Zed\AgentDashboardMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\MerchantUserGuiTableConfigurationProviderInterface;
use Spryker\Zed\AgentDashboardMerchantPortalGui\Communication\GuiTable\DataProvider\MerchantUserGuiTableDataProvider;
use Spryker\Zed\AgentDashboardMerchantPortalGui\Communication\GuiTable\Mapper\MerchantUserGuiTableMapper;
use Spryker\Zed\AgentDashboardMerchantPortalGui\Communication\GuiTable\Mapper\MerchantUserGuiTableMapperInterface;
use Spryker\Zed\AgentDashboardMerchantPortalGui\Dependency\Facade\AgentDashboardMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\AgentDashboardMerchantPortalGui\Dependency\Facade\AgentDashboardMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\AgentDashboardMerchantPortalGui\AgentDashboardMerchantPortalGuiConfig getConfig()
 */
class AgentDashboardMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\AgentDashboardMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\MerchantUserGuiTableConfigurationProviderInterface
     */
    public function createMerchantUserGuiTableConfigurationProvider(): MerchantUserGuiTableConfigurationProviderInterface
    {
        return new MerchantUserGuiTableConfigurationProvider(
            $this->getGuiTableFactory(),
            $this->getTranslatorFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface
     */
    public function createMerchantUserGuiTableDataProvider(): GuiTableDataProviderInterface
    {
        return new MerchantUserGuiTableDataProvider(
            $this->getMerchantUserFacade(),
            $this->getTranslatorFacade(),
            $this->createMerchantUserGuiTableMapper(),
            $this->getMerchantUserTableDataExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\AgentDashboardMerchantPortalGui\Communication\GuiTable\Mapper\MerchantUserGuiTableMapperInterface
     */
    public function createMerchantUserGuiTableMapper(): MerchantUserGuiTableMapperInterface
    {
        return new MerchantUserGuiTableMapper();
    }

    /**
     * @return \Spryker\Zed\AgentDashboardMerchantPortalGui\Dependency\Facade\AgentDashboardMerchantPortalGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): AgentDashboardMerchantPortalGuiToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(AgentDashboardMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\AgentDashboardMerchantPortalGui\Dependency\Facade\AgentDashboardMerchantPortalGuiToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): AgentDashboardMerchantPortalGuiToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(AgentDashboardMerchantPortalGuiDependencyProvider::FACADE_TRANSLATOR);
    }

    /**
     * @return \Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface
     */
    public function getGuiTableHttpDataRequestExecutor(): GuiTableDataRequestExecutorInterface
    {
        return $this->getProvidedDependency(AgentDashboardMerchantPortalGuiDependencyProvider::SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR);
    }

    /**
     * @return \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    public function getGuiTableFactory(): GuiTableFactoryInterface
    {
        return $this->getProvidedDependency(AgentDashboardMerchantPortalGuiDependencyProvider::SERVICE_GUI_TABLE_FACTORY);
    }

    /**
     * @return array<\Spryker\Zed\AgentDashboardMerchantPortalGuiExtension\Dependency\Plugin\MerchantUserTableDataExpanderPluginInterface>
     */
    protected function getMerchantUserTableDataExpanderPlugins(): array
    {
        return $this->getProvidedDependency(AgentDashboardMerchantPortalGuiDependencyProvider::PLUGINS_MERCHANT_USER_TABLE_DATA_EXPANDER);
    }
}
