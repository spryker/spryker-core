<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url;

use Spryker\Zed\Propel\Communication\Plugin\Connection;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Url\Dependency\UrlToLocaleBridge;
use Spryker\Zed\Url\Dependency\UrlToTouchBridge;

class UrlDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_LOCALE = 'locale facade';
    const FACADE_TOUCH = 'touch facade';
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
            return (new Connection())->get();
        };

        return $container;
    }

}
