<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CompanyBusinessUnitDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_COMPANY_BUSINESS_UNIT_POST_SAVE = 'PLUGINS_COMPANY_BUSINESS_UNIT_POST_SAVE';
    public const PLUGINS_COMPANY_BUSINESS_UNIT_PRE_DELETE = 'PLUGINS_COMPANY_BUSINESS_UNIT_PRE_DELETE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addCompanyBusinessUnitPostSavePlugins($container);
        $container = $this->addCompanyBusinessUnitPreDeletePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyBusinessUnitPostSavePlugins(Container $container): Container
    {
        $container[static::PLUGINS_COMPANY_BUSINESS_UNIT_POST_SAVE] = function (Container $container) {
            return $this->getCompanyBusinessUnitPostSavePlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyBusinessUnitPreDeletePlugins(Container $container): Container
    {
        $container[static::PLUGINS_COMPANY_BUSINESS_UNIT_PRE_DELETE] = function (Container $container) {
            return $this->getCompanyBusinessUnitPreDeletePlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitExtension\Dependency\Plugin\CompanyBusinessUnitPostSavePluginInterface[]
     */
    protected function getCompanyBusinessUnitPostSavePlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitExtension\Dependency\Plugin\CompanyBusinessUnitPreDeletePluginInterface[]
     */
    protected function getCompanyBusinessUnitPreDeletePlugins(): array
    {
        return [];
    }
}
