<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application;

use Spryker\Shared\Url\UrlBuilder;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ApplicationDependencyProvider extends AbstractBundleDependencyProvider
{

    const URL_BUILDER = 'URL_BUILDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::URL_BUILDER] = function (Container $container) {
            return new UrlBuilder();
        };

        return $container;
    }
}
