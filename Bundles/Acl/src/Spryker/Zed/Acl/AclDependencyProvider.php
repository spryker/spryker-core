<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl;

use Spryker\Zed\Acl\Dependency\Facade\AclToRouterFacadeBridge;
use Spryker\Zed\Acl\Dependency\Facade\AclToRouterFacadeInterface;
use Spryker\Zed\Acl\Dependency\Facade\AclToUserBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Acl\AclConfig getConfig()
 */
class AclDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_USER = 'user facade';

    /**
     * @var string
     */
    public const FACADE_ACL = 'acl facade';

    /**
     * @var string
     */
    public const QUERY_CONTAINER_USER = 'user query container';

    /**
     * @var string
     */
    public const QUERY_CONTAINER_ACL = 'acl query container';

    /**
     * @var string
     */
    public const SERVICE_DATE_FORMATTER = 'date formatter service';

    /**
     * @var string
     */
    public const ACL_INSTALLER_PLUGINS = 'ACL_INSTALLER_PLUGINS';

    /**
     * @var string
     */
    public const PLUGINS_ACL_ROLES_EXPANDER = 'PLUGINS_ACL_ROLES_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_ACL_ROLE_POST_SAVE = 'PLUGINS_ACL_ROLE_POST_SAVE';

    /**
     * @var string
     */
    public const PLUGINS_ACL_ACCESS_CHECKER_STRATEGY = 'PLUGINS_ACL_ACCESS_CHECKER_STRATEGY';

    /**
     * @var string
     */
    public const FACADE_ROUTER = 'FACADE_ROUTER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addFacadeUser($container);
        $container = $this->addAclQueryContainer($container);
        $container = $this->addRouterFacade($container);

        $container->set(static::SERVICE_DATE_FORMATTER, function (Container $container) {
            return $container->getLocator()->utilDateTime()->service();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addFacadeUser($container);
        $container = $this->addAclInstallerPlugins($container);
        $container = $this->addAclRolesExpanderPlugins($container);
        $container = $this->addAclRolePostSavePlugins($container);
        $container = $this->addAclAccessCheckerStrategyPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container->set(static::QUERY_CONTAINER_USER, function (Container $container) {
            return $container->getLocator()->user()->queryContainer();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRouterFacade(Container $container): Container
    {
        $container->set(static::FACADE_ROUTER, function (Container $container): AclToRouterFacadeInterface {
            return new AclToRouterFacadeBridge($container->getLocator()->router()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFacadeUser(Container $container)
    {
        $container->set(static::FACADE_USER, function (Container $container) {
            return new AclToUserBridge($container->getLocator()->user()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAclQueryContainer(Container $container)
    {
        /** @deprecated Use {@link \Spryker\Zed\Acl\Business\AclBusinessFactory::getQueryContainer()} directly for the own module's query container */
        $container->set(static::QUERY_CONTAINER_ACL, function (Container $container) {
            return $container->getLocator()->acl()->queryContainer();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAclInstallerPlugins(Container $container): Container
    {
        $container->set(static::ACL_INSTALLER_PLUGINS, function () {
            return $this->getAclInstallerPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\AclExtension\Dependency\Plugin\AclInstallerPluginInterface>
     */
    protected function getAclInstallerPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAclRolesExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ACL_ROLES_EXPANDER, function () {
            return $this->getAclRolesExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\AclExtension\Dependency\Plugin\AclRolesExpanderPluginInterface>
     */
    protected function getAclRolesExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAclRolePostSavePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ACL_ROLE_POST_SAVE, function () {
            return $this->getAclRolePostSavePlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\AclExtension\Dependency\Plugin\AclRolePostSavePluginInterface>
     */
    protected function getAclRolePostSavePlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAclAccessCheckerStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ACL_ACCESS_CHECKER_STRATEGY, function () {
            return $this->getAclAccessCheckerStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\AclExtension\Dependency\Plugin\AclAccessCheckerStrategyPluginInterface>
     */
    protected function getAclAccessCheckerStrategyPlugins(): array
    {
        return [];
    }
}
