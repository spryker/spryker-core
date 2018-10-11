<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage;

use Spryker\Zed\CategoryImage\Dependency\Facade\CategoryImageToLocale;
use Spryker\Zed\Kernel\Container;

class CategoryImageDependencyProvider
{
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new CategoryImageToLocale($container->getLocator()->locale()->facade());
        };

        return $container;
    }
}
