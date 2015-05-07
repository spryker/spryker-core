<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

interface OptionContainerInterface
{
    /**
     * @return OptionItemInterface[]
     */
    public function getOptions();

    /**
     * @param \ArrayObject $options
     *
     * @return $this
     */
    public function setOptions(\ArrayObject $options);
}
