<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher\Business\Merger;

use Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface;

class PublisherPluginMerger implements PublisherPluginMergerInterface
{
    /**
     * @var \Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherRegistryPluginInterface[]
     */
    protected $publisherRegistryPlugins = [];

    /**
     * @var \Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface
     */
    protected $publisherEventRegistry;

    /**
     * @var array
     */
    protected static $eventCollectionBuffer = [];

    /**
     * @param \Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherRegistryPluginInterface[] $publisherRegistryPlugins
     * @param \Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface $publisherEventRegistry
     */
    public function __construct(array $publisherRegistryPlugins, PublisherEventRegistryInterface $publisherEventRegistry)
    {
        $this->publisherRegistryPlugins = $publisherRegistryPlugins;
        $this->publisherEventRegistry = $publisherEventRegistry;
    }

    /**
     * @return array
     */
    public function mergePublisherPlugins(): array
    {
        if (!static::$eventCollectionBuffer) {
            static::$eventCollectionBuffer = $this->extractEventCollection();
        }

        return static::$eventCollectionBuffer;
    }

    /**
     * @return array
     */
    protected function extractEventCollection(): array
    {
        $eventCollection = [];
        foreach ($this->publisherRegistryPlugins as $publisherRegistryPlugin) {
            $this->publisherEventRegistry = $publisherRegistryPlugin->expandPublisherEventRegistry($this->publisherEventRegistry);
        }

        foreach ($this->publisherEventRegistry as $eventName => $listeners) {
            $eventCollection[$eventName] = $listeners;
        }

        return $eventCollection;
    }
}
