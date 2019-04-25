<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Publisher\Business\Merger\PublisherPluginMerger;
use Spryker\Zed\Publisher\Business\Merger\PublisherPluginMergerInterface;
use Spryker\Zed\Publisher\Business\Registry\PublisherEventRegistry;
use Spryker\Zed\Publisher\PublisherDependencyProvider;

/**
 * @method \Spryker\Zed\Publisher\PublisherConfig getConfig()
 */
class PublisherBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Publisher\Business\Merger\PublisherPluginMergerInterface
     */
    public function createPublisherPluginMerger(): PublisherPluginMergerInterface
    {
        return new PublisherPluginMerger(
            $this->getPublisherRegistryPlugins(),
            $this->createPublisherEventRegistry()
        );
    }

    /**
     * @return \Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherRegistryPluginInterface[]
     */
    public function getPublisherRegistryPlugins(): array
    {
        return $this->getProvidedDependency(PublisherDependencyProvider::PUBLISHER_REGISTRY_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Publisher\Business\Registry\PublisherEventRegistry
     */
    public function createPublisherEventRegistry(): PublisherEventRegistry
    {
        return new PublisherEventRegistry();
    }
}
