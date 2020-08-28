<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Testify\Helper\Business;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use SprykerTest\Zed\Testify\Helper\AbstractDependencyProviderHelper;

class DependencyProviderHelper extends AbstractDependencyProviderHelper
{
    /**
     * @param \Spryker\Zed\Kernel\AbstractBundleDependencyProvider $dependencyProvider
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provide(AbstractBundleDependencyProvider $dependencyProvider, Container $container): Container
    {
        return $dependencyProvider->provideBusinessLayerDependencies($container);
    }
}
