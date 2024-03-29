<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage;

use Spryker\Zed\CompanyUserStorage\Dependency\Facade\CompanyUserStorageToCompanyUserFacadeBridge;
use Spryker\Zed\CompanyUserStorage\Dependency\Facade\CompanyUserStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CompanyUserStorage\CompanyUserStorageConfig getConfig()
 */
class CompanyUserStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGINS_COMPANY_USER_STORAGE_EXPANDER = 'PLUGINS_COMPANY_USER_STORAGE_EXPANDER';

    /**
     * @var string
     */
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    /**
     * @var string
     */
    public const FACADE_COMPANY_USER = 'FACADE_COMPANY_USER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addEventBehaviorFacade($container);
        $container = $this->addCompanyUserFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addCompanyUserFacade($container);
        $container = $this->addCompanyUserStorageExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new CompanyUserStorageToEventBehaviorFacadeBridge(
                $container->getLocator()->eventBehavior()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_COMPANY_USER, function (Container $container) {
            return new CompanyUserStorageToCompanyUserFacadeBridge(
                $container->getLocator()->companyUser()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserStorageExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_COMPANY_USER_STORAGE_EXPANDER, function () {
            return $this->getCompanyUserStorageExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\CompanyUserStorageExtension\Dependency\Plugin\CompanyUserStorageExpanderPluginInterface>
     */
    protected function getCompanyUserStorageExpanderPlugins(): array
    {
        return [];
    }
}
