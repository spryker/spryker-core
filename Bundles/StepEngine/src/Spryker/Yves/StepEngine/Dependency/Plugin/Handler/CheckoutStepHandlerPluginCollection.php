<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngine\Dependency\Plugin\Handler;

class CheckoutStepHandlerPluginCollection
{

    /**
     * @var \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\CheckoutStepHandlerPluginInterface[]
     */
    private $checkoutStepHandler = [];

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\CheckoutStepHandlerPluginInterface $checkoutStepHandlerPlugin
     * @param string $name
     *
     * @return $this
     */
    public function add(CheckoutStepHandlerPluginInterface $checkoutStepHandlerPlugin, $name)
    {
        $this->checkoutStepHandler[$name] = $checkoutStepHandlerPlugin;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\CheckoutStepHandlerPluginInterface
     */
    public function get($name)
    {
        return $this->checkoutStepHandler[$name];
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return isset($this->checkoutStepHandler[$name]);
    }

}
