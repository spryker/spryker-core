<?php

namespace Spryker\Zed\Publisher;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Publisher\Communication\Collection\PublisherRegistryCollection;
use Spryker\Zed\Publisher\Communication\Collection\PublisherRegistryCollectionInterface;

class PublisherDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PUBLISHER_REGISTRY_COLLECTION = 'PUBLISHER_REGISTRY_COLLECTION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::PUBLISHER_REGISTRY_COLLECTION] = function (Container $container) {
            return $this->getPublisherRegistryCollection();
        };
    }

    /**
     * @return PublisherRegistryCollectionInterface
     */
    public function getPublisherRegistryCollection()
    {
        return new PublisherRegistryCollection();
    }

}
