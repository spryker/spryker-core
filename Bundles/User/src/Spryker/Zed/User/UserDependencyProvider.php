<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\User\Dependency\Plugin\GroupPlugin;

class UserDependencyProvider extends AbstractBundleDependencyProvider
{
    const PLUGIN_GROUP = 'group plugin';
    const PLUGINS_USERS_TABLE_EXTENDER = 'PLUGINS_USERS_TABLE_EXTENDER';
    const CLIENT_SESSION = 'client session';
    const SERVICE_DATE_FORMATTER = 'date formatter service';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addSession($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addDateFormatter($container);
        $container = $this->addGroupPlugin($container);
        $container = $this->addUsersTableExtenderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSession(Container $container)
    {
        $container[static::CLIENT_SESSION] = function (Container $container) {
            return $container->getLocator()->session()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDateFormatter(Container $container)
    {
        $container[static::SERVICE_DATE_FORMATTER] = function (Container $container) {
            return $container->getLocator()->utilDateTime()->service();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGroupPlugin(Container $container)
    {
        $container[static::PLUGIN_GROUP] = function (Container $container) {
            return new GroupPlugin();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUsersTableExtenderPlugins(Container $container)
    {
        $container[static::PLUGINS_USERS_TABLE_EXTENDER] = function (Container $container) {
            return $this->getUsersTableExtenderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\User\Dependency\Plugin\UsersTableExpanderPluginInterface[]
     */
    protected function getUsersTableExtenderPlugins()
    {
        return [];
    }
}
