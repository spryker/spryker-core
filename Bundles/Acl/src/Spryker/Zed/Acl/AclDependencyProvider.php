<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl;

use Spryker\Zed\Acl\Dependency\Facade\AclToUserBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Acl\AclConfig getConfig()
 */
class AclDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_USER = 'user facade';
    public const FACADE_ACL = 'acl facade';
    public const QUERY_CONTAINER_USER = 'user query container';
    public const QUERY_CONTAINER_ACL = 'acl query container';
    public const SERVICE_DATE_FORMATTER = 'date formatter service';
    public const ACL_INSTALLER_PLUGINS = 'ACL_INSTALLER_PLUGINS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addFacadeUser($container);
        $container = $this->addAclQueryContainer($container);

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
        /** @deprecated Use {@link getQueryContainer()} directly for the own bundle's query container */
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
     * @return \Spryker\Zed\AclExtension\Dependency\Plugin\AclInstallerPluginInterface[]
     */
    protected function getAclInstallerPlugins(): array
    {
        return [];
    }
}
