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

class UrlDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_LOCALE = 'locale facade';
    const FACADE_TOUCH = 'touch facade';

    const PLUGINS_URL_BEFORE_CREATE = 'PLUGINS_URL_BEFORE_CREATE';
    const PLUGINS_URL_AFTER_CREATE = 'PLUGINS_URL_AFTER_CREATE';
    const PLUGINS_URL_BEFORE_UPDATE = 'PLUGINS_URL_BEFORE_UPDATE';
    const PLUGINS_URL_AFTER_UPDATE = 'PLUGINS_URL_AFTER_UPDATE';
    const PLUGINS_URL_BEFORE_DELETE = 'PLUGINS_URL_BEFORE_DELETE';
    const PLUGINS_URL_AFTER_DELETE = 'PLUGINS_URL_AFTER_DELETE';
    /**
     * @deprecated Use `getConnection()` method from query container instead.
     */
    const PLUGIN_PROPEL_CONNECTION = 'propel connection plugin';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new UrlToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_TOUCH] = function (Container $container) {
            return new UrlToTouchBridge($container->getLocator()->touch()->facade());
        };

        $container[self::PLUGIN_PROPEL_CONNECTION] = function () {
            return Propel::getConnection();
        };

        $container[self::PLUGINS_URL_BEFORE_CREATE] = function () {
            return $this->getUrlBeforeCreatePlugins();
        };

        $container[self::PLUGINS_URL_AFTER_CREATE] = function () {
            return $this->getUrlAfterCreatePlugins();
        };

        $container[self::PLUGINS_URL_BEFORE_UPDATE] = function () {
            return $this->getUrlBeforeUpdatePlugins();
        };

        $container[self::PLUGINS_URL_AFTER_UPDATE] = function () {
            return $this->getUrlAfterUpdatePlugins();
        };

        $container[self::PLUGINS_URL_BEFORE_DELETE] = function () {
            return $this->getUrlBeforeDeletePlugins();
        };

        $container[self::PLUGINS_URL_AFTER_DELETE] = function () {
            return $this->getUrlAfterDeletePlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\Url\Dependency\Plugin\UrlCreatePluginInterface[]
     */
    protected function getUrlBeforeCreatePlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\Url\Dependency\Plugin\UrlCreatePluginInterface[]
     */
    protected function getUrlAfterCreatePlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\Url\Dependency\Plugin\UrlUpdatePluginInterface[]
     */
    protected function getUrlBeforeUpdatePlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\Url\Dependency\Plugin\UrlUpdatePluginInterface[]
     */
    protected function getUrlAfterUpdatePlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\Url\Dependency\Plugin\UrlUpdatePluginInterface[]
     */
    protected function getUrlBeforeDeletePlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\Url\Dependency\Plugin\UrlUpdatePluginInterface[]
     */
    protected function getUrlAfterDeletePlugins()
    {
        return [];
    }

}
