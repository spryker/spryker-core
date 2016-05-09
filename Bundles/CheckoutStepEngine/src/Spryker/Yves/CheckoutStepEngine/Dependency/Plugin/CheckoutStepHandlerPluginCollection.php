<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CheckoutStepEngine\Dependency\Plugin;

class CheckoutStepHandlerPluginCollection
{

    /**
     * @var \Spryker\Yves\CheckoutStepEngine\Dependency\Plugin\CheckoutStepHandlerPluginInterface[]
     */
    private $checkoutStepHandler = [];

    /**
     * @param \Spryker\Yves\CheckoutStepEngine\Dependency\Plugin\CheckoutStepHandlerPluginInterface $checkoutStepHandlerPlugin
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
     * @return \Spryker\Yves\CheckoutStepEngine\Dependency\Plugin\CheckoutStepHandlerPluginInterface
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
