<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\OrderStateMachine\TimeoutProcessor;

use ArrayAccess;
use Spryker\Zed\Oms\Business\Exception\TimeoutProcessorPluginNotFoundException;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\HasAwareCollectionInterface;
use Spryker\Zed\OmsExtension\Dependency\Plugin\TimeoutProcessorPluginInterface;

class TimeoutProcessorCollection implements TimeoutProcessorCollectionInterface, HasAwareCollectionInterface, ArrayAccess
{
    /**
     * @var \Spryker\Zed\OmsExtension\Dependency\Plugin\TimeoutProcessorPluginInterface[]
     */
    protected $timeoutProcessorPlugins = [];

    /**
     * @param \Spryker\Zed\OmsExtension\Dependency\Plugin\TimeoutProcessorPluginInterface[] $timeoutProcessorPlugins
     */
    public function __construct(array $timeoutProcessorPlugins = [])
    {
        $this->setTimeoutProcessorPlugins($timeoutProcessorPlugins);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has($name): bool
    {
        return isset($this->timeoutProcessorPlugins[$name]);
    }

    /**
     * @param string $name
     *
     * @throws \Spryker\Zed\Oms\Business\Exception\TimeoutProcessorPluginNotFoundException
     *
     * @return \Spryker\Zed\OmsExtension\Dependency\Plugin\TimeoutProcessorPluginInterface
     */
    public function get(string $name): TimeoutProcessorPluginInterface
    {
        if (!$this->has($name)) {
            throw new TimeoutProcessorPluginNotFoundException(
                sprintf('Could not find timeout processor plugin "%s". You need to add the needed plugin within your DependencyInjector.', $name)
            );
        }

        return $this->timeoutProcessorPlugins[$name];
    }

    /**
     * @param string $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * @param string $offset
     *
     * @return \Spryker\Zed\OmsExtension\Dependency\Plugin\TimeoutProcessorPluginInterface
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param string $offset
     * @param \Spryker\Zed\OmsExtension\Dependency\Plugin\TimeoutProcessorPluginInterface $value
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->add($value, $offset);
    }

    /**
     * @param string $offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->timeoutProcessorPlugins[$offset]);
    }

    /**
     * @param \Spryker\Zed\OmsExtension\Dependency\Plugin\TimeoutProcessorPluginInterface $timeoutProcessorPlugin
     * @param string $name
     *
     * @return void
     */
    protected function add(TimeoutProcessorPluginInterface $timeoutProcessorPlugin, string $name): void
    {
        if ($this->has($name)) {
            return;
        }

        $this->timeoutProcessorPlugins[$name] = $timeoutProcessorPlugin;
    }

    /**
     * @param \Spryker\Zed\OmsExtension\Dependency\Plugin\TimeoutProcessorPluginInterface[] $timeoutProcessorPlugins
     *
     * @return void
     */
    protected function setTimeoutProcessorPlugins(array $timeoutProcessorPlugins): void
    {
        foreach ($timeoutProcessorPlugins as $timeoutProcessorPlugin) {
            $this->timeoutProcessorPlugins[$timeoutProcessorPlugin->getName()] = $timeoutProcessorPlugin;
        }
    }
}
