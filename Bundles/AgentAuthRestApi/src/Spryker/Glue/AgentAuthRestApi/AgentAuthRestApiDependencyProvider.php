<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

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

        $container = $this->addOauthClient($container);

        return $container;


    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addOauthClient(\Spryker\Glue\Kernel\Container $container): \Spryker\Glue\Kernel\Container
    {
        $container->set(static::CLIENT_OAUTH, function (\Spryker\Glue\Kernel\Container $container) {
            return new \Spryker\Glue\AgentAuthRestApi\Dependency\Client\AgentAuthRestApiToOauthClientBridge(
                $container->getLocator()->oauth()->client()
            );
        });

        return $container;
    }
}
