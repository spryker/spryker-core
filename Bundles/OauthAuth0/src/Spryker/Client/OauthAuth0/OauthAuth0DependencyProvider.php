<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthAuth0;

use Riskio\OAuth2\Client\Provider\Auth0;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\OauthAuth0\Dependency\External\Auth0Adapter;

/**
 * @method \Spryker\Client\OauthAuth0\OauthAuth0Config getConfig()
 */
class OauthAuth0DependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_AUTH0_ADAPTER = 'CLIENT_AUTH0_ADAPTER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addAuth0ClientAdapter($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addAuth0ClientAdapter(Container $container): Container
    {
        $container->set(static::CLIENT_AUTH0_ADAPTER, function () {
            return new Auth0Adapter(
                new Auth0(
                    [
                        'clientId' => $this->getConfig()->getClientId(),
                        'clientSecret' => $this->getConfig()->getClientSecret(),
                        'customDomain' => $this->getConfig()->getCustomDomain(),
                    ],
                ),
            );
        });

        return $container;
    }
}
