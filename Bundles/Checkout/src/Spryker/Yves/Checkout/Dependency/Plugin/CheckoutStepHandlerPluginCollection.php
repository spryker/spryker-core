<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Checkout\Dependency\Plugin;

class CheckoutStepHandlerPluginCollection implements \Iterator, \Countable
{

    /**
     * @var \Spryker\Yves\Checkout\Dependency\Plugin\CheckoutStepHandlerPluginInterface[]
     */
    private $checkoutStepHandler = [];

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @param \Spryker\Yves\Checkout\Dependency\Plugin\CheckoutStepHandlerPluginInterface $checkoutStepHandlerPlugin
     * @param string $paymentMethod
     *
     * @return $this
     */
    public function add(CheckoutStepHandlerPluginInterface $checkoutStepHandlerPlugin, $paymentMethod)
    {
        $this->checkoutStepHandler[$paymentMethod] = $checkoutStepHandlerPlugin;

        return $this;
    }

    /**
     * @param string $paymentMethod
     *
     * @return \Spryker\Yves\Checkout\Dependency\Plugin\CheckoutStepHandlerPluginInterface
     */
    public function get($paymentMethod)
    {
        return $this->checkoutStepHandler[$paymentMethod];
    }

    /**
     * @param string $paymentMethod
     *
     * @return bool
     */
    public function has($paymentMethod)
    {
        return isset($this->checkoutStepHandler[$paymentMethod]);
    }

    /**
     * @return \Spryker\Yves\Checkout\Dependency\Plugin\CheckoutStepHandlerPluginInterface
     */
    public function current()
    {
        return $this->checkoutStepHandler[$this->position];
    }

    /**
     * @return void
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->checkoutStepHandler[$this->position]);
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->checkoutStepHandler);
    }

}
