<?php

namespace Spryker\Zed\CompanySupplierGui;

use Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToCompanySupplierFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CompanySupplierGuiDependencyProvider extends AbstractBundleDependencyProvider
{

    public const FACADE_COMPANY_SUPPLIER = 'FACADE_COMPANY_SUPPLIER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addCompanySupplierFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanySupplierFacade(Container $container): Container
    {
        $container[static::FACADE_COMPANY_SUPPLIER] = function (Container $container) {
            return new CompanySupplierGuiToCompanySupplierFacadeBridge($container->getLocator()->companySupplier()->facade());
        };

        return $container;
    }

}
