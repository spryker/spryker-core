<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

use Generated\Shared\Calculation\OrderItemOptionInterface;

interface OptionContainerInterface
{
    /**
     * @return OrderItemOptionInterface[]
     */
    public function getOptions();

    /**
     * @param \ArrayObject $options
     *
     * @return $this
     */
    public function setOptions(\ArrayObject $options);
}
