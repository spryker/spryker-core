<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckSearch;

use Orm\Zed\PublishAndSynchronizeHealthCheck\Persistence\SpyPublishAndSynchronizeHealthCheckQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Dependency\Client\PublishAndSynchronizeHealthCheckSearchToSearchClientBridge;
use Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Dependency\Facade\PublishAndSynchronizeHealthCheckSearchToEventBehaviorFacadeBridge;

/**
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\PublishAndSynchronizeHealthCheckSearchConfig getConfig()
 */
class PublishAndSynchronizeHealthCheckSearchDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    /**
     * @var string
     */
    public const CLIENT_SEARCH = 'CLIENT_SEARCH';

    /**
     * @var string
     */
    public const PROPEL_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_QUERY = 'PROPEL_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_QUERY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addEventBehaviorFacade($container);
        $container = $this->addSearchClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = $this->addPublishAndSynchronizeHealthCheckQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new PublishAndSynchronizeHealthCheckSearchToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPublishAndSynchronizeHealthCheckQuery(Container $container): Container
    {
        $container->set(static::PROPEL_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_QUERY, $container->factory(function (): SpyPublishAndSynchronizeHealthCheckQuery {
            return SpyPublishAndSynchronizeHealthCheckQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSearchClient(Container $container): Container
    {
        $container->set(static::CLIENT_SEARCH, function (Container $container) {
            return new PublishAndSynchronizeHealthCheckSearchToSearchClientBridge($container->getLocator()->search()->client());
        });

        return $container;
    }
}
