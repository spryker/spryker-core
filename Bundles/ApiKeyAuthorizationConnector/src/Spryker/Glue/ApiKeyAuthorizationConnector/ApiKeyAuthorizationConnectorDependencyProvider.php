<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ApiKeyAuthorizationConnector;

use Spryker\Glue\ApiKeyAuthorizationConnector\Dependency\Facade\ApiKeyAuthorizationConnectorToApiKeyBridge;
use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container;

/**
 * @method \Spryker\Glue\ApiKeyAuthorizationConnector\ApiKeyAuthorizationConnectorConfig getConfig()
 */
class ApiKeyAuthorizationConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_API_KEY = 'FACADE_API_KEY';

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    public function provideBackendDependencies(Container $container): Container
    {
        $container = parent::provideBackendDependencies($container);
        $container = $this->addApiKeyFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container$container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addApiKeyFacade(Container $container): Container
    {
        $container->set(static::FACADE_API_KEY, function (Container $container) {
            return new ApiKeyAuthorizationConnectorToApiKeyBridge(
                $container->getLocator()->apiKey()->facade(),
            );
        });

        return $container;
    }
}
