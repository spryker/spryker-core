<?php

namespace Spryker\Zed\Publishing;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Publishing\Dependency\PublisherRegistryCollection;
use Spryker\Zed\PublishingExtension\Dependency\PublisherRegistryCollectionInterface;

class PublishingDependencyProvider extends AbstractBundleDependencyProvider
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
            return $this->getPublisherRegistryCollection($container);
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
