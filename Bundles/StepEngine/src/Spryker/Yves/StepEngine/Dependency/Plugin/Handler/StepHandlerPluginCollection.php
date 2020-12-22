<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngine\Dependency\Plugin\Handler;

class StepHandlerPluginCollection
{
    /**
     * @var \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface[]
     */
    protected $stepHandler = [];

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface $stepHandlerPlugin
     * @param string $name
     *
     * @return $this
     */
    public function add(StepHandlerPluginInterface $stepHandlerPlugin, $name)
    {
        $this->stepHandler[$name] = $stepHandlerPlugin;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface
     */
    public function get($name)
    {
        return $this->stepHandler[$name];
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return isset($this->stepHandler[$name]);
    }
}
