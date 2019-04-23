<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Publisher\Communication\Collection\PublisherRegistryCollectionInterface;
use Spryker\Zed\Publisher\Communication\Registry\PublisherEventRegistry;
use Spryker\Zed\Publisher\PublisherDependencyProvider;

/**
 * @method \Spryker\Zed\Publisher\PublisherConfig getConfig()
 */
class PublisherCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Publisher\Communication\Collection\PublisherRegistryCollectionInterface
     */
    public function getPublisherRegistryCollection(): PublisherRegistryCollectionInterface
    {
        return $this->getProvidedDependency(PublisherDependencyProvider::PUBLISHER_REGISTRY_COLLECTION);
    }

    /**
     * @return \Spryker\Zed\Publisher\Communication\Registry\PublisherEventRegistry
     */
    public function createPublisherEventRegistry(): PublisherEventRegistry
    {
        return new PublisherEventRegistry();
    }
}
