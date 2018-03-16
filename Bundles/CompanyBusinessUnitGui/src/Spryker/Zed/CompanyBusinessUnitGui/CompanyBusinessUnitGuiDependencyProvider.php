<?php

namespace Spryker\Zed\CompanyBusinessUnitGui;

use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CompanyBusinessUnitGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const COMPANY_BUSINESS_UNIT_QUERY = 'COMPANY_BUSINESS_UNIT_QUERY';
    public const COMPANY_BUSINESS_UNIT_FACADE = 'COMPANY_BUSINESS_UNIT_FACADE';

    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addCompanyBusinessUnitQuery($container);
        $container = $this->addCompanyBusinessUnitFacade($container);

        return $container;
    }

    protected function addCompanyBusinessUnitQuery(Container $container): Container
    {
        $container[static::COMPANY_BUSINESS_UNIT_QUERY] = function (Container $container) {
            return SpyCompanyBusinessUnitQuery::create();
        };

        return $container;
    }

    protected function addCompanyBusinessUnitFacade(Container $container): Container
    {
        $container[static::COMPANY_BUSINESS_UNIT_FACADE] = function (Container $container) {
            return new CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeBridge(
                $container->getLocator()->companyBusinessUnit()->facade()
            );
        };

        return $container;
    }
}