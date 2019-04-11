<?php

namespace Spryker\Zed\Publishing\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Publishing\PublishingDependencyProvider;
use Spryker\Zed\PublishingExtension\Dependency\PublisherRegistryCollectionInterface;

/**
 * @method \Spryker\Zed\Publishing\PublishingConfig getConfig()
 */
class PublishingCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return PublisherRegistryCollectionInterface
     */
    public function getPublisherRegistryCollection()
    {
        return $this->getProvidedDependency(PublishingDependencyProvider::PUBLISHER_REGISTRY_COLLECTION);
    }
}
