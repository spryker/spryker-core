<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Oauth;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Oauth\Dependency\Client\OauthToZedRequestClientBridge;
use Spryker\Client\OauthCryptography\Communication\Plugin\Oauth\BearerTokenAuthorizationValidatorPlugin;
use Spryker\Client\OauthCryptography\Communication\Plugin\Oauth\FileSystemKeyLoaderPlugin;

/**
 * @method \Spryker\Client\Oauth\OauthConfig getConfig()
 */
class OauthDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';
    public const PLUGINS_KEY_LOADER = 'PLUGINS_KEY_LOADER';
    public const PLUGINS_AUTHORIZATION_VALIDATOR = 'PLUGINS_AUTHORIZATION_VALIDATOR';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addZedRequestClient($container);
        $container = $this->addKeyLoaderPlugins($container);
        $container = $this->addAuthorizationValidatorPlugins($container);

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
        $container[static::PLUGINS_KEY_LOADER] = function () {
            return $this->getKeyLoaderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\OauthExtention\Dependency\Plugin\KeyLoaderPluginInterface[]
     */
    protected function getKeyLoaderPlugins(): array
    {
        return [
            // Todo: wire on project instead
            new FileSystemKeyLoaderPlugin(),
        ];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addAuthorizationValidatorPlugins(Container $container): Container
    {
        $container[static::PLUGINS_AUTHORIZATION_VALIDATOR] = function () {
            return $this->getAuthorizationValidatorPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\OauthExtention\Dependency\Plugin\AuthorizationValidatorPluginInterface[]
     */
    protected function getAuthorizationValidatorPlugins(): array
    {
        return [
            // Todo: wire on project instead
            new BearerTokenAuthorizationValidatorPlugin(),
        ];
    }
}
