<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Publisher\Communication\Collection\PublisherRegistryCollection;
use Spryker\Zed\Publisher\Communication\Collection\PublisherRegistryCollectionInterface;

/**
 * @method \Spryker\Zed\Publisher\PublisherConfig getConfig()
 */
class PublisherDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PUBLISHER_REGISTRY_COLLECTION = 'PUBLISHER_REGISTRY_COLLECTION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    public function provideCommunicationLayerDependencies(Container $container): void
    {
        $container[static::PUBLISHER_REGISTRY_COLLECTION] = function (Container $container) {
            return $this->getPublisherRegistryCollection();
        };
    }

    /**
     * @return \Spryker\Zed\Publisher\Communication\Collection\PublisherRegistryCollectionInterface
     */
    public function getPublisherRegistryCollection(): PublisherRegistryCollectionInterface
    {
        return new PublisherRegistryCollection();
    }
}
