<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui;

use Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleToCompanyUserQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CompanyRoleGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_COMPANY_ROLE_TO_COMPANY_USER_QUERY = 'PROPEL_COMPANY_ROLE_TO_COMPANY_USER_QUERY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addPropelCompanyRoleToCompanyUserQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPropelCompanyRoleToCompanyUserQuery(Container $container): Container
    {
        $container[static::PROPEL_COMPANY_ROLE_TO_COMPANY_USER_QUERY] = function (Container $container) {
            return SpyCompanyRoleToCompanyUserQuery::create();
        };

        return $container;
    }
}
