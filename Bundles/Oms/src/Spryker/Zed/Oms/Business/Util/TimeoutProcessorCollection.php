<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Util;

use Spryker\Zed\Oms\Business\Exception\TimeoutProcessorPluginNotFoundException;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\HasAwareCollectionInterface;
use Spryker\Zed\OmsExtension\Dependency\Plugin\TimeoutProcessorPluginInterface;

class TimeoutProcessorCollection implements TimeoutProcessorCollectionInterface, HasAwareCollectionInterface
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
