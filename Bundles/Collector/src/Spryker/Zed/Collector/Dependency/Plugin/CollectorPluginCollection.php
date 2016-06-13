<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Dependency\Plugin;

use Spryker\Zed\Collector\Exception\CollectorPluginNotFoundException;

class CollectorPluginCollection extends \ArrayObject implements CollectorPluginCollectionInterface
{

    /**
     * @var CollectorPluginInterface[]
     */
    protected $collectorPlugins = [];

    public function __construct()
    {
        parent::__construct($this->collectorPlugins);
    }

    /**
     * @param \Spryker\Zed\Collector\Dependency\Plugin\CollectorPluginInterface $collectorPlugin
     * @param $type
     *
     * @return $this
     */
    public function addPlugin(CollectorPluginInterface $collectorPlugin, $type)
    {
        $this->collectorPlugins[$type] = $collectorPlugin;

        return $this;
    }

    /**
     * @param string $type
     *
     * @throws \Spryker\Zed\Collector\Exception\CollectorPluginNotFoundException
     *
     * @return \Spryker\Zed\Collector\Dependency\Plugin\CollectorPluginInterface
     */
    public function getPlugin($type)
    {
        if (!$this->hasPlugin($type)) {
            throw new CollectorPluginNotFoundException(sprintf('Could not find collector plugin for type "%s". You need to register a plugin.', $type));
        }

        return $this->collectorPlugins[$type];
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function hasPlugin($type)
    {
        return (isset($this->collectorPlugins[$type]));
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return array_keys($this->collectorPlugins);
    }

}
