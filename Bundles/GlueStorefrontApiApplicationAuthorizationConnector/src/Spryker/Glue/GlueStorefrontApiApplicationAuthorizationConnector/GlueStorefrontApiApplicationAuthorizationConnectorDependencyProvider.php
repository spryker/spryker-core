<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector;

use Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\Dependency\Client\GlueStorefrontApiApplicationAuthorizationConnectorToAuthorizationClientBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

class GlueStorefrontApiApplicationAuthorizationConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_AUTHORIZATION = 'CLIENT_AUTHORIZATION';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
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
            return new GlueStorefrontApiApplicationAuthorizationConnectorToAuthorizationClientBridge($container->getLocator()->authorization()->client());
        });

        return $container;
    }
}
