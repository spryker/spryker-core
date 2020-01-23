<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher\Business\Collator;

use Spryker\Zed\PublisherExtension\Dependency\PublisherEventRegistryInterface;

class PublisherEventCollator implements PublisherEventCollatorInterface
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
     * @var string[]
     */
    protected static $eventCollectionBuffer;

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
     * @return string[]
     */
    public function getPublisherEventCollection(): array
    {
        if (static::$eventCollectionBuffer === null) {
            static::$eventCollectionBuffer = $this->getEventCollection();
        }

        return static::$eventCollectionBuffer;
    }

    /**
     * @return string[]
     */
    protected function getEventCollection(): array
    {
        foreach ($this->publisherRegistryPlugins as $publisherRegistryPlugin) {
            $this->publisherEventRegistry = $publisherRegistryPlugin->expandPublisherEventRegistry($this->publisherEventRegistry);
        }

        $eventCollection = [];

        foreach ($this->publisherEventRegistry as $eventName => $listeners) {
            $eventCollection[$eventName] = $listeners;
        }

        return $eventCollection;
    }
}
