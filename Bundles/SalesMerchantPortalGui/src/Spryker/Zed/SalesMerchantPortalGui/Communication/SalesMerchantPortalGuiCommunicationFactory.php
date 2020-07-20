<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Communication;

use Spryker\Zed\GuiTable\Communication\ConfigurationProvider\GuiTableConfigurationProviderInterface;
use Spryker\Zed\GuiTable\Communication\DataProvider\GuiTableDataProviderInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SalesMerchantPortalGui\Communication\ConfigurationProvider\MerchantOrderGuiTableConfigurationProvider;
use Spryker\Zed\SalesMerchantPortalGui\Communication\DataProvider\MerchantOrderTableDataProvider;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToCurrencyFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToGuiTableFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantOmsFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantSalesOrderFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMoneyFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToSalesFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToStoreFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\SalesMerchantPortalGuiDependencyProvider;

/**
 * @method \Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface getRepository()
 */
class SalesMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\GuiTable\Communication\ConfigurationProvider\GuiTableConfigurationProviderInterface
     */
    public function createMerchantOrderGuiTableConfigurationProvider(): GuiTableConfigurationProviderInterface
    {
        return new MerchantOrderGuiTableConfigurationProvider(
            $this->getStoreFacade(),
            $this->getMerchantOmsFacade(),
            $this->getMerchantUserFacade()
        );
    }

    /**
     * @return \Spryker\Zed\GuiTable\Communication\DataProvider\GuiTableDataProviderInterface
     */
    public function createMerchantOrderTableDataProvider(): GuiTableDataProviderInterface
    {
        return new MerchantOrderTableDataProvider(
            $this->getRepository(),
            $this->getMerchantUserFacade(),
            $this->getCurrencyFacade(),
            $this->getMoneyFacade()
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
     * @return \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToGuiTableFacadeInterface
     */
    public function getGuiTableFacade(): SalesMerchantPortalGuiToGuiTableFacadeInterface
    {
        return $this->getProvidedDependency(SalesMerchantPortalGuiDependencyProvider::FACADE_GUI_TABLE);
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
     * @return \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToSalesFacadeInterface
     */
    public function getSalesFacade(): SalesMerchantPortalGuiToSalesFacadeInterface
    {
        return $this->getProvidedDependency(SalesMerchantPortalGuiDependencyProvider::FACADE_SALES);
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
}
