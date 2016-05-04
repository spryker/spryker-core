<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Checkout\Dependency\Plugin;

class CheckoutSubFormPluginCollection implements \Iterator, \Countable
{

    /**
     * @var \Spryker\Yves\Checkout\Dependency\Plugin\CheckoutSubFormPluginInterface[]
     */
    private $checkoutSubForm = [];

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @param \Spryker\Yves\Checkout\Dependency\Plugin\CheckoutSubFormPluginInterface $checkoutSubForm
     *
     * @return $this
     */
    public function add(CheckoutSubFormPluginInterface $checkoutSubForm)
    {
        $this->checkoutSubForm[] = $checkoutSubForm;

        return $this;
    }

    /**
     * @return \Spryker\Yves\Checkout\Dependency\Plugin\CheckoutSubFormPluginInterface
     */
    public function current()
    {
        return current($this->checkoutSubForm);
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
        return isset($this->checkoutSubForm[$this->position]);
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
        return count($this->checkoutSubForm);
    }

}
