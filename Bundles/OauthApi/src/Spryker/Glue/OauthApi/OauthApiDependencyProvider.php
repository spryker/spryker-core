<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\OauthApi\Dependency\Client\OauthApiToAuthenticationClientBridge;
use Spryker\Glue\OauthApi\Dependency\Client\OauthApiToOauthClientBridge;
use Spryker\Glue\OauthApi\Dependency\Service\OauthApiToOauthServiceBridge;
use Spryker\Glue\OauthApi\Dependency\Service\OauthApiToUtilEncodingServiceBridge;

/**
 * @method \Spryker\Glue\OauthApi\OauthApiConfig getConfig()
 */
class OauthApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_AUTHENTICATION = 'CLIENT_AUTHENTICATION';

    /**
     * @var string
     */
    public const SERVICE_OAUTH = 'SERVICE_OAUTH';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const CLIENT_OAUTH = 'CLIENT_OAUTH';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addAuthenticationClient($container);
        $container = $this->addOauthService($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addOauthClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addOauthService(Container $container): Container
    {
        $container->set(static::SERVICE_OAUTH, function (Container $container) {
            return new OauthApiToOauthServiceBridge(
                $container->getLocator()->oauth()->service(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new OauthApiToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service(),
            );
        });

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
            return new OauthApiToOauthClientBridge(
                $container->getLocator()->oauth()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addAuthenticationClient(Container $container): Container
    {
        $container->set(static::CLIENT_AUTHENTICATION, function (Container $container) {
            return new OauthApiToAuthenticationClientBridge($container->getLocator()->authentication()->client());
        });

        return $container;
    }
}
