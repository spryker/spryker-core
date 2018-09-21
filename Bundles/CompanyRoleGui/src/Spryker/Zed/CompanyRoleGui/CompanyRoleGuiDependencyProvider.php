<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui;

use Spryker\Zed\CompanyRoleGui\Dependency\Facade\CompanyRoleGuiToCompanyRoleFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CompanyRoleGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_COMPANY_ROLE = 'FACADE_COMPANY_ROLE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addCompanyRoleFacade($container);

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
}
