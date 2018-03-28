<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui;

use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Spryker\Zed\CompanyGui\Dependency\Facade\CompanyGuiToCompanyFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CompanyGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_COMPANY_QUERY = 'PROPEL_COMPANY_QUERY';
    public const FACADE_COMPANY = 'FACADE_COMPANY';
    public const COMPANY_TABLE_ACTION_EXTENSION_PLUGINS = 'COMPANY_TABLE_ACTION_EXTENSION_PLUGINS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addPropelCompanyQuery($container);
        $container = $this->addCompanyFacade($container);
        $container = $this->addCompanyTableActionExtensionPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPropelCompanyQuery(Container $container): Container
    {
        $container[static::PROPEL_COMPANY_QUERY] = function (Container $container) {
            return SpyCompanyQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyFacade(Container $container): Container
    {
        $container[static::FACADE_COMPANY] = function (Container $container) {
            return new CompanyGuiToCompanyFacadeBridge($container->getLocator()->company()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyTableActionExtensionPlugins(Container $container): Container
    {
        $container[static::COMPANY_TABLE_ACTION_EXTENSION_PLUGINS] = function (Container $container) {
            return $this->getCompanyTableActionExtensionPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\CompanyGuiExtension\Dependency\Plugin\CompanyTableActionExtensionInterface[]
     */
    protected function getCompanyTableActionExtensionPlugins(): array
    {
        return [];
    }
}
