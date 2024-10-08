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
    /**
     * @var string
     */
    public const PLUGIN_GROUP = 'group plugin';

    /**
     * @var string
     */
    public const PLUGINS_USERS_TABLE_EXTENDER = 'PLUGINS_USERS_TABLE_EXTENDER';

    /**
     * @var string
     */
    public const PLUGINS_USER_TABLE_ACTION_EXPANDER = 'PLUGINS_USERS_TABLE_ACTION_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_USER_TABLE_CONFIG_EXPANDER = 'PLUGINS_USER_TABLE_CONFIG_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_USER_TABLE_DATA_EXPANDER = 'PLUGINS_USER_TABLE_DATA_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_POST_SAVE = 'PLUGINS_POST_SAVE';

    /**
     * @var string
     */
    public const PLUGINS_USER_PRE_SAVE = 'PLUGINS_USER_PRE_SAVE';

    /**
     * @deprecated Use {@link \Spryker\Zed\User\UserDependencyProvider::PLUGINS_USER_EXPANDER} instead.
     *
     * @var string
     */
    public const PLUGINS_USER_TRANSFER_EXPANDER = 'PLUGINS_USER_TRANSFER_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_USER_FORM_EXPANDER = 'PLUGINS_USER_FORM_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_USER_EXPANDER = 'PLUGINS_USER_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_USER_QUERY_CRITERIA_EXPANDER = 'PLUGINS_USER_QUERY_CRITERIA_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_USER_POST_CREATE = 'PLUGINS_USER_POST_CREATE';

    /**
     * @var string
     */
    public const PLUGINS_USER_POST_UPDATE = 'PLUGINS_USER_POST_UPDATE';

    /**
     * @var string
     */
    public const CLIENT_SESSION = 'client session';

    /**
     * @var string
     */
    public const SERVICE_DATE_FORMATTER = 'date formatter service';

    /**
     * @uses \Spryker\Zed\Http\Communication\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK
     *
     * @var string
     */
    public const SERVICE_REQUEST_STACK = 'request_stack';

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
        $container = $this->addUserExpanderPlugins($container);
        $container = $this->addUserPostCreatePlugins($container);
        $container = $this->addUserPostUpdatePlugins($container);

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
        $container = $this->addRequestStackService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);

        $container = $this->addUserQueryCriteriaExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSession(Container $container)
    {
        $container->set(static::CLIENT_SESSION, function (Container $container) {
            return $container->getLocator()->session()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDateFormatter(Container $container)
    {
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
    protected function addRequestStackService(Container $container): Container
    {
        $container->set(static::SERVICE_REQUEST_STACK, function (Container $container) {
            return $container->hasApplicationService(static::SERVICE_REQUEST_STACK)
                ? $container->getApplicationService(static::SERVICE_REQUEST_STACK)
                : null;
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGroupPlugin(Container $container)
    {
        $container->set(static::PLUGIN_GROUP, function (Container $container) {
            return new GroupPlugin();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserTableActionExpanderPlugins(Container $container)
    {
        $container->set(static::PLUGINS_USER_TABLE_ACTION_EXPANDER, function () {
            return array_merge(
                $this->getUserTableActionExpanderPlugins(),
                $this->getUsersTableExtenderPlugins(),
            );
        });

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
     * @return array<\Spryker\Zed\UserExtension\Dependency\Plugin\UserTableActionExpanderPluginInterface>
     */
    protected function getUserTableActionExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\User\UserDependencyProvider::getUserTableActionExpanderPlugins()} instead.
     *
     * @return array<\Spryker\Zed\User\Dependency\Plugin\UsersTableExpanderPluginInterface>
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
        $container->set(static::PLUGINS_POST_SAVE, function (): array {
            return $this->getPostSavePlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\UserExtension\Dependency\Plugin\UserPostSavePluginInterface>
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
        $container->set(static::PLUGINS_USER_PRE_SAVE, function (): array {
            return $this->getUserPreSavePlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\UserExtension\Dependency\Plugin\UserPreSavePluginInterface>
     */
    protected function getUserPreSavePlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\User\UserDependencyProvider::addUserExpanderPlugins()} instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserTransferExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_USER_TRANSFER_EXPANDER, function (): array {
            return $this->getUserTransferExpanderPlugins();
        });

        return $container;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\User\UserDependencyProvider::getUserExpanderPlugins()} instead.
     *
     * @return array<\Spryker\Zed\UserExtension\Dependency\Plugin\UserPreSavePluginInterface>
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
        $container->set(static::PLUGINS_USER_TABLE_CONFIG_EXPANDER, function (): array {
            return $this->getUserTableConfigExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\UserExtension\Dependency\Plugin\UserTableConfigExpanderPluginInterface>
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
        $container->set(static::PLUGINS_USER_TABLE_DATA_EXPANDER, function (): array {
            return $this->getUserTableDataExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\UserExtension\Dependency\Plugin\UserTableDataExpanderPluginInterface>
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
        $container->set(static::PLUGINS_USER_FORM_EXPANDER, function (): array {
            return $this->getUserFormExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\UserExtension\Dependency\Plugin\UserFormExpanderPluginInterface>
     */
    protected function getUserFormExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_USER_EXPANDER, function () {
            return $this->getUserExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\UserExtension\Dependency\Plugin\UserExpanderPluginInterface>
     */
    protected function getUserExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserQueryCriteriaExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_USER_QUERY_CRITERIA_EXPANDER, function () {
            return $this->getUserQueryCriteriaExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\UserExtension\Dependency\Plugin\UserQueryCriteriaExpanderPluginInterface>
     */
    protected function getUserQueryCriteriaExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserPostCreatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_USER_POST_CREATE, function () {
            return $this->getUserPostCreatePlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\UserExtension\Dependency\Plugin\UserPostCreatePluginInterface>
     */
    protected function getUserPostCreatePlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserPostUpdatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_USER_POST_UPDATE, function () {
            return $this->getUserPostUpdatePlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\UserExtension\Dependency\Plugin\UserPostUpdatePluginInterface>
     */
    protected function getUserPostUpdatePlugins(): array
    {
        return [];
    }
}
