<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi;

use Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

class AuthRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_OAUTH = 'CLIENT_OAUTH';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addOauthClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addOauthClient(Container $container): Container
    {
        $container[static::CLIENT_OAUTH] = function (Container $container) {
            return new AuthRestApiToOauthClientBridge($container->getLocator()->oauth()->client());
        };

        return $container;
    }
}
