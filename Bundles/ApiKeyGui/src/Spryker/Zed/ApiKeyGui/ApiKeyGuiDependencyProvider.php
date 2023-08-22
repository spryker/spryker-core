<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKeyGui;

use Orm\Zed\ApiKey\Persistence\SpyApiKeyQuery;
use Spryker\Zed\ApiKeyGui\Dependency\Facade\ApiKeyGuiToApiKeyFacadeBridge;
use Spryker\Zed\ApiKeyGui\Dependency\Service\ApiKeyGuiToUtilTextServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\ApiKeyGui\ApiKeyGuiConfig getConfig()
 */
class ApiKeyGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PROPEL_QUERY_API_KEY = 'PROPEL_QUERY_API_KEY';

    /**
     * @var string
     */
    public const FACADE_API_KEY = 'FACADE_API_KEY';

    /**
     * @var string
     */
    public const SERVICE_UTIL_TEXT = 'SERVICE_UTIL_TEXT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addApiKeyPropelQuery($container);
        $container = $this->addApiKeyFacade($container);
        $container = $this->addUtilTextService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addApiKeyPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_API_KEY, $container->factory(function () {
            return SpyApiKeyQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addApiKeyFacade(Container $container): Container
    {
        $container->set(static::FACADE_API_KEY, function (Container $container) {
            return new ApiKeyGuiToApiKeyFacadeBridge(
                $container->getLocator()->apiKey()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilTextService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_TEXT, function (Container $container) {
            return new ApiKeyGuiToUtilTextServiceBridge($container->getLocator()->utilText()->service());
        });

        return $container;
    }
}
