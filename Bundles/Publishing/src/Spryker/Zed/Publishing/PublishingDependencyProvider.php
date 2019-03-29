<?php

namespace Spryker\Zed\Publishing;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Publishing\Dependency\PublishingRegistryCollection;
use Spryker\Zed\PublishingExtension\Dependency\PublishingRegistryCollectionInterface;

class PublishingDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PUBLISHING_REGISTRY_COLLECTION = 'PUBLISHING_REGISTRY_COLLECTION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::PUBLISHING_REGISTRY_COLLECTION] = function (Container $container) {
            return $this->getPublishingRegistryCollection($container);
        };
    }

    /**
     * @return PublishingRegistryCollectionInterface
     */
    public function getPublishingRegistryCollection()
    {
        return new PublishingRegistryCollection();
    }

}
