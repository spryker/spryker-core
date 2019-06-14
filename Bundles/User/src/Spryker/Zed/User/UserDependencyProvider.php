<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\User\Dependency\Plugin\GroupPlugin;

/**
 * @method \Spryker\Zed\User\UserConfig getConfig()
 */
class UserDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGIN_GROUP = 'group plugin';
    public const PLUGINS_USERS_TABLE_EXTENDER = 'PLUGINS_USERS_TABLE_EXTENDER';
    public const PLUGINS_USER_TABLE_ACTION_EXPANDER = 'PLUGINS_USERS_TABLE_ACTION_EXPANDER';
    public const PLUGINS_USER_TABLE_CONFIG_EXPANDER = 'PLUGINS_USER_TABLE_CONFIG_EXPANDER';
    public const PLUGINS_USER_TABLE_DATA_EXPANDER = 'PLUGINS_USER_TABLE_DATA_EXPANDER';
    public const PLUGINS_POST_SAVE = 'PLUGINS_POST_SAVE';
    public const PLUGINS_USER_PRE_SAVE = 'PLUGINS_USER_PRE_SAVE';
    public const PLUGINS_USER_TRANSFER_EXPANDER = 'PLUGINS_USER_TRANSFER_EXPANDER';
    public const PLUGINS_USER_FORM_EXPANDER = 'PLUGINS_USER_FORM_EXPANDER';
    public const CLIENT_SESSION = 'client session';
    public const SERVICE_DATE_FORMATTER = 'date formatter service';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addSession($container);
        $container = $this->addPostSavePlugins($container);
        $container = $this->addUserPreSavePlugins($container);
        $container = $this->addUserTransferExpanderPlugins($container);

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
        $container = $this->addUserTableActionExpanderPlugins($container);
        $container = $this->addUserTableConfigExpanderPlugins($container);
        $container = $this->addUserTableDataExpanderPlugins($container);
        $container = $this->addUserFormExpanderPlugins($container);
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
    protected function addUserTableActionExpanderPlugins(Container $container)
    {
        $container[static::PLUGINS_USER_TABLE_ACTION_EXPANDER] = function () {
            return array_merge(
                $this->getUserTableActionExpanderPlugins(),
                $this->getUsersTableExtenderPlugins()
            );
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
        return $this->addUserTableActionExpanderPlugins($container);
    }

    /**
     * @return \Spryker\Zed\UserExtension\Dependency\Plugin\UserTableActionExpanderPluginInterface[]
     */
    protected function getUserTableActionExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Use \Spryker\Zed\User\UserDependencyProvider::getUserTableActionExpanderPlugins() instead.
     *
     * @return \Spryker\Zed\User\Dependency\Plugin\UsersTableExpanderPluginInterface[]
     */
    protected function getUsersTableExtenderPlugins()
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPostSavePlugins(Container $container): Container
    {
        $container[static::PLUGINS_POST_SAVE] = function (): array {
            return $this->getPostSavePlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\UserExtension\Dependency\Plugin\UserPostSavePluginInterface[]
     */
    protected function getPostSavePlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserPreSavePlugins(Container $container): Container
    {
        $container[static::PLUGINS_USER_PRE_SAVE] = function (): array {
            return $this->getUserPreSavePlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\UserExtension\Dependency\Plugin\UserPreSavePluginInterface[]
     */
    protected function getUserPreSavePlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserTransferExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_USER_TRANSFER_EXPANDER] = function (): array {
            return $this->getUserTransferExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\UserExtension\Dependency\Plugin\UserPreSavePluginInterface[]
     */
    protected function getUserTransferExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserTableConfigExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_USER_TABLE_CONFIG_EXPANDER] = function (): array {
            return $this->getUserTableConfigExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\UserExtension\Dependency\Plugin\UserTableConfigExpanderPluginInterface[]
     */
    protected function getUserTableConfigExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserTableDataExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_USER_TABLE_DATA_EXPANDER] = function (): array {
            return $this->getUserTableDataExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\UserExtension\Dependency\Plugin\UserTableDataExpanderPluginInterface[]
     */
    protected function getUserTableDataExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserFormExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_USER_FORM_EXPANDER] = function (): array {
            return $this->getUserFormExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\UserExtension\Dependency\Plugin\UserFormExpanderPluginInterface[]
     */
    protected function getUserFormExpanderPlugins(): array
    {
        return [];
    }
}
