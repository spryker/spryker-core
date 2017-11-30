<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage;

use Spryker\Zed\GlossaryStorage\Dependency\QueryContainer\GlossaryStorageToGlossaryQueryContainerBridge;
use Spryker\Zed\GlossaryStorage\Dependency\Service\GlossaryStorageToUtilSynchronizationBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class GlossaryStorageDependencyProvider extends AbstractBundleDependencyProvider
{

    const SERVICE_UTIL_SYNCHRONIZATION = 'SERVICE_UTIL_SYNCHRONIZATION';
    const QUERY_CONTAINER_GLOSSARY = 'QUERY_CONTAINER_GLOSSARY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::SERVICE_UTIL_SYNCHRONIZATION] = function (Container $container) {
            return new GlossaryStorageToUtilSynchronizationBridge($container->getLocator()->utilSynchronization()->service());
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
