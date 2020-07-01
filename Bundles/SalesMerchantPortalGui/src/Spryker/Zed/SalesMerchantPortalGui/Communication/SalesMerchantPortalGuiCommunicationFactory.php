<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SalesMerchantPortalGui\Communication\DataProvider\OrdersDashboardCardProvider;
use Spryker\Zed\SalesMerchantPortalGui\Communication\DataProvider\OrdersDashboardCardProviderInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToRouterFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\SalesMerchantPortalGuiDependencyProvider;

/**
 * @method \Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesMerchantPortalGui\SalesMerchantPortalGuiConfig getConfig()
 */
class SalesMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
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
     * @return \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToRouterFacadeInterface
     */
    public function getRouterFacade(): SalesMerchantPortalGuiToRouterFacadeInterface
    {
        return $this->getProvidedDependency(SalesMerchantPortalGuiDependencyProvider::FACADE_ROUTER);
    }

    /**
     * @return \Twig\Environment
     */
    protected function getTwigEnvironment()
    {
        return $this->getProvidedDependency(SalesMerchantPortalGuiDependencyProvider::SERVICE_TWIG);
    }
}
