<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthPermission;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\OauthPermission\Dependency\Facade\OauthPermissionToPermissionFacadeBridge;

/**
 * @method \Spryker\Zed\OauthPermission\OauthPermissionConfig getConfig()
 */
class OauthPermissionDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PERMISSION = 'FACADE_PERMISSION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addPermissionFacade($container);

        return $container;
    }

    protected function addPermissionFacade(Container $container): Container
    {
        $container[static::FACADE_PERMISSION] = function (Container $container) {
            return new OauthPermissionToPermissionFacadeBridge($container->getLocator()->permission()->facade());
        };

        return $container;
    }
}
