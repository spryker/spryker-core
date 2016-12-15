<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation;

use Spryker\Shared\Url\UrlBuilder;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Navigation\Dependency\Util\NavigationToUtilEncodingBridge;

class NavigationDependencyProvider extends AbstractBundleDependencyProvider
{

    const URL_BUILDER = 'url builder';
    const SERVICE_ENCODING = 'util encoding service';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addUrlBuilder($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUrlBuilder(Container $container)
    {
        $container[static::URL_BUILDER] = function () {
            return new UrlBuilder();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container)
    {
        $container[static::SERVICE_ENCODING] = function (Container $container) {
            $navigationToUtilEncodingBridger = new NavigationToUtilEncodingBridge(
                $container->getLocator()->utilEncoding()->service()
            );

            return $navigationToUtilEncodingBridger;
        };

        return $container;
    }

}
