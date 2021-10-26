<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url;

use Propel\Runtime\Propel;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Url\Dependency\UrlToLocaleBridge;
use Spryker\Zed\Url\Dependency\UrlToTouchBridge;

/**
 * @method \Spryker\Zed\Url\UrlConfig getConfig()
 */
class UrlDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_LOCALE = 'locale facade';

    /**
     * @var string
     */
    public const FACADE_TOUCH = 'touch facade';

    /**
     * @var string
     */
    public const PLUGINS_URL_BEFORE_CREATE = 'PLUGINS_URL_BEFORE_CREATE';

    /**
     * @var string
     */
    public const PLUGINS_URL_AFTER_CREATE = 'PLUGINS_URL_AFTER_CREATE';

    /**
     * @var string
     */
    public const PLUGINS_URL_BEFORE_UPDATE = 'PLUGINS_URL_BEFORE_UPDATE';

    /**
     * @var string
     */
    public const PLUGINS_URL_AFTER_UPDATE = 'PLUGINS_URL_AFTER_UPDATE';

    /**
     * @var string
     */
    public const PLUGINS_URL_BEFORE_DELETE = 'PLUGINS_URL_BEFORE_DELETE';

    /**
     * @var string
     */
    public const PLUGINS_URL_AFTER_DELETE = 'PLUGINS_URL_AFTER_DELETE';

    /**
     * @deprecated Use the `getConnection()` method from query container instead.
     *
     * @var string
     */
    public const PLUGIN_PROPEL_CONNECTION = 'propel connection plugin';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new UrlToLocaleBridge($container->getLocator()->locale()->facade());
        });

        $container->set(static::FACADE_TOUCH, function (Container $container) {
            return new UrlToTouchBridge($container->getLocator()->touch()->facade());
        });

        $container->set(static::PLUGIN_PROPEL_CONNECTION, function () {
            return Propel::getConnection();
        });

        $container->set(static::PLUGINS_URL_BEFORE_CREATE, function () {
            return $this->getUrlBeforeCreatePlugins();
        });

        $container->set(static::PLUGINS_URL_AFTER_CREATE, function () {
            return $this->getUrlAfterCreatePlugins();
        });

        $container->set(static::PLUGINS_URL_BEFORE_UPDATE, function () {
            return $this->getUrlBeforeUpdatePlugins();
        });

        $container->set(static::PLUGINS_URL_AFTER_UPDATE, function () {
            return $this->getUrlAfterUpdatePlugins();
        });

        $container->set(static::PLUGINS_URL_BEFORE_DELETE, function () {
            return $this->getUrlBeforeDeletePlugins();
        });

        $container->set(static::PLUGINS_URL_AFTER_DELETE, function () {
            return $this->getUrlAfterDeletePlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\Url\Dependency\Plugin\UrlCreatePluginInterface>
     */
    protected function getUrlBeforeCreatePlugins()
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\Url\Dependency\Plugin\UrlCreatePluginInterface>
     */
    protected function getUrlAfterCreatePlugins()
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\Url\Dependency\Plugin\UrlUpdatePluginInterface>
     */
    protected function getUrlBeforeUpdatePlugins()
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\Url\Dependency\Plugin\UrlUpdatePluginInterface>
     */
    protected function getUrlAfterUpdatePlugins()
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\Url\Dependency\Plugin\UrlDeletePluginInterface>
     */
    protected function getUrlBeforeDeletePlugins()
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\Url\Dependency\Plugin\UrlDeletePluginInterface>
     */
    protected function getUrlAfterDeletePlugins()
    {
        return [];
    }
}
