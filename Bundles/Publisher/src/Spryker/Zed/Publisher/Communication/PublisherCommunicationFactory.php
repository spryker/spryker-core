<?php

namespace Spryker\Zed\Publisher\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Publisher\PublisherDependencyProvider;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherRegistryCollectionInterface;

/**
 * @method \Spryker\Zed\Publisher\PublisherConfig getConfig()
 */
class PublisherCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return PublisherRegistryCollectionInterface
     */
    public function getPublisherRegistryCollection()
    {
        return $this->getProvidedDependency(PublisherDependencyProvider::PUBLISHER_REGISTRY_COLLECTION);
    }
}
