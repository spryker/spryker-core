<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi;

use Spryker\Glue\AgentAuthRestApi\Dependency\Client\AgentAuthRestApiToOauthClientBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\AgentAuthRestApi\AgentAuthRestApiConfig getConfig()
 */
class AgentAuthRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_OAUTH = 'CLIENT_OAUTH';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

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
        $container->set(static::CLIENT_OAUTH, function (Container $container) {
            return new AgentAuthRestApiToOauthClientBridge(
                $container->getLocator()->oauth()->client()
            );
        });

        return $container;
    }
}
