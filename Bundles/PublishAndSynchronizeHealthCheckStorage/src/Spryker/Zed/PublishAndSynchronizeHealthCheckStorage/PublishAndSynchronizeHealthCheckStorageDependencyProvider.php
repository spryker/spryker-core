<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckStorage;

use Orm\Zed\PublishAndSynchronizeHealthCheck\Persistence\SpyPublishAndSynchronizeHealthCheckQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Dependency\Client\PublishAndSynchronizeHealthCheckStorageToStorageClientBridge;
use Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Dependency\Facade\PublishAndSynchronizeHealthCheckStorageToEventBehaviorFacade;

/**
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\PublishAndSynchronizeHealthCheckStorageConfig getConfig()
 */
class PublishAndSynchronizeHealthCheckStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    /**
     * @var string
     */
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';

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
        $container = $this->addStorageClient($container);

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
            return new PublishAndSynchronizeHealthCheckStorageToEventBehaviorFacade($container->getLocator()->eventBehavior()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORAGE, function (Container $container) {
            return new PublishAndSynchronizeHealthCheckStorageToStorageClientBridge($container->getLocator()->storage()->client());
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
}
