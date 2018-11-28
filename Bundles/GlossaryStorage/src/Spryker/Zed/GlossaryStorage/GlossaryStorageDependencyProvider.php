<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage;

use Spryker\Zed\GlossaryStorage\Dependency\Facade\GlossaryStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\GlossaryStorage\Dependency\QueryContainer\GlossaryStorageToGlossaryQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\GlossaryStorage\GlossaryStorageConfig getConfig()
 */
class GlossaryStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const QUERY_CONTAINER_GLOSSARY = 'QUERY_CONTAINER_GLOSSARY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new GlossaryStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container[static::QUERY_CONTAINER_GLOSSARY] = function (Container $container) {
            return new GlossaryStorageToGlossaryQueryContainerBridge($container->getLocator()->glossary()->queryContainer());
        };

        return $container;
    }
}
