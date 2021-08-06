<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationAuthorizationConnector;

use Spryker\Glue\GlueApplicationAuthorizationConnector\Dependency\Client\GlueApplicationAuthorizationConnectorToAuthorizationClientBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

class GlueApplicationAuthorizationConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_AUTHORIZATION = 'CLIENT_AUTHORIZATION';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addAuthorizationClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addAuthorizationClient(Container $container): Container
    {
        $container->set(static::CLIENT_AUTHORIZATION, function (Container $container) {
            return new GlueApplicationAuthorizationConnectorToAuthorizationClientBridge($container->getLocator()->authorization()->client());
        });

        return $container;
    }
}
