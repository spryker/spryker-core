<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Oauth;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Oauth\Dependency\Client\OauthToZedRequestClientBridge;

/**
 * @method \Spryker\Client\Oauth\OauthConfig getConfig()
 */
class OauthDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';

    /**
     * @var string
     */
    public const PLUGINS_KEY_LOADER = 'PLUGINS_KEY_LOADER';

    /**
     * @var string
     */
    public const PLUGINS_AUTHORIZATION_VALIDATOR = 'PLUGINS_AUTHORIZATION_VALIDATOR';

    /**
     * @var string
     */
    public const PLUGINS_ACCESS_TOKEN_VALIDATOR = 'PLUGINS_ACCESS_TOKEN_VALIDATOR';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addZedRequestClient($container);
        $container = $this->addKeyLoaderPlugins($container);
        $container = $this->addAuthorizationValidatorPlugins($container);
        $container = $this->addAccessTokenValidatorPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addZedRequestClient(Container $container): Container
    {
        $container->set(static::CLIENT_ZED_REQUEST, function (Container $container) {
            return new OauthToZedRequestClientBridge($container->getLocator()->zedRequest()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addKeyLoaderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_KEY_LOADER, function () {
            return $this->getKeyLoaderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Client\OauthExtension\Dependency\Plugin\KeyLoaderPluginInterface>
     */
    protected function getKeyLoaderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addAuthorizationValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_AUTHORIZATION_VALIDATOR, function () {
            return $this->getAuthorizationValidatorPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Client\OauthExtension\Dependency\Plugin\AuthorizationValidatorPluginInterface>
     */
    protected function getAuthorizationValidatorPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addAccessTokenValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ACCESS_TOKEN_VALIDATOR, function (): array {
            return $this->getAccessTokenValidatorPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Client\OauthExtension\Dependency\Plugin\AccessTokenValidatorPluginInterface>
     */
    protected function getAccessTokenValidatorPlugins(): array
    {
        return [];
    }
}
