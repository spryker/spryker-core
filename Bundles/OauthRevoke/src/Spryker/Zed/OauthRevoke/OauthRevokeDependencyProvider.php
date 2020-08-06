<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\OauthRevoke\Dependency\Service\OauthRevokeToUtilEncodingServiceBridge;

/**
 * @method \Spryker\Zed\OauthRevoke\OauthRevokeConfig getConfig()
 */
class OauthRevokeDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';
    public const PLUGINS_OAUTH_USER_IDENTIFIER_FILTER = 'PLUGINS_OAUTH_USER_IDENTIFIER_FILTER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addUtilEncodingService($container);
        $container = $this->addOauthUserIdentifierFilterPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new OauthRevokeToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthUserIdentifierFilterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_OAUTH_USER_IDENTIFIER_FILTER, function () {
            return $this->getOauthUserIdentifierFilterPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\OauthRevokeExtension\Dependency\Plugin\OauthUserIdentifierFilterPluginInterface[]
     */
    protected function getOauthUserIdentifierFilterPlugins(): array
    {
        return [];
    }
}
