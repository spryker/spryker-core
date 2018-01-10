<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CompanyDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_COMPANY_SAVE = 'PLUGINS_COMPANY_SAVE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addCompanySavePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanySavePlugins(Container $container)
    {
        $container[static::PLUGINS_COMPANY_SAVE] = function (Container $container) {
            return $this->getCompanySavePlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\Company\Dependency\CompanySavePluginInterface[]
     */
    protected function getCompanySavePlugins()
    {
        return [];
    }
}
