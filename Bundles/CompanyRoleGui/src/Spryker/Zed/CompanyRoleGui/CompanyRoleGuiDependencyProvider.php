<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui;

use Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery;
use Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToCompanyRoleFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CompanyRoleGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_COMPANY_ROLE = 'FACADE_COMPANY_ROLE';
    public const PROPEL_COMPANY_ROLE_QUERY = 'PROPEL_COMPANY_ROLE_QUERY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addCompanyRoleFacade($container);
        $container = $this->addPropelCompanyRoleQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addCompanyRoleFacade(Container $container): Container
    {
        $container[static::FACADE_COMPANY_ROLE] = function (Container $container) {
            return new CompanyRoleGuiToCompanyRoleFacadeBridge(
                $container->getLocator()->companyRole()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPropelCompanyRoleQuery(Container $container): Container
    {
        $container[static::PROPEL_COMPANY_ROLE_QUERY] = function (Container $container) {
            return SpyCompanyRoleQuery::create();
        };

        return $container;
    }
}
