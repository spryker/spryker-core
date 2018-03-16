<?php

namespace Spryker\Zed\CompanyBusinessUnitGui;

use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CompanyBusinessUnitGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const COMPANY_BUSINESS_UNIT_QUERY = 'COMPANY_BUSINESS_UNIT_QUERY';

    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addCompanyBusinessUnitQuery($container);

        return $container;
    }

    protected function addCompanyBusinessUnitQuery(Container $container): Container
    {
        $container[static::COMPANY_BUSINESS_UNIT_QUERY] = function (Container $container) {
            return SpyCompanyBusinessUnitQuery::create();
        };

        return $container;
    }
}