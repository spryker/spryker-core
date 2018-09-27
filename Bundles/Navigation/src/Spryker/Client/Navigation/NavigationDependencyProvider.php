<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Navigation;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class NavigationDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $this->provideStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return void
     */
    protected function provideStorageClient(Container $container)
    {
        $container[self::CLIENT_STORAGE] = function (Container $container) {
            return $container->getLocator()->storage()->client();
        };
    }
}
