<?php

namespace Spryker\Zed\Publishing\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Publishing\PublishingDependencyProvider;
use Spryker\Zed\PublishingExtension\Dependency\PublishingRegistryCollectionInterface;

/**
 * @method \Spryker\Zed\Publishing\PublishingConfig getConfig()
 */
class PublishingCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return PublishingRegistryCollectionInterface
     */
    public function getProcessorMessagePlugins()
    {
        return $this->getProvidedDependency(PublishingDependencyProvider::PUBLISHING_REGISTRY_COLLECTION);
    }
}
