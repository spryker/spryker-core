<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SecurityBlockerRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\SecurityBlockerRestApi\Dependency\Client\SecurityBlockerRestApiToSecurityBlockerClientBridge;

/**
 * @method \Spryker\Glue\SecurityBlockerRestApi\SecurityBlockerRestApiConfig getConfig()
 */
class SecurityBlockerRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_SECURITY_BLOCKER = 'CLIENT_SECURITY_BLOCKER';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addSecurityBlockerClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addSecurityBlockerClient(Container $container): Container
    {
        $container->set(static::CLIENT_SECURITY_BLOCKER, function (Container $container) {
            new SecurityBlockerRestApiToSecurityBlockerClientBridge(
                $container->getLocator()->securityBlocker()->client()
            );
        });

        return $container;
    }
}
