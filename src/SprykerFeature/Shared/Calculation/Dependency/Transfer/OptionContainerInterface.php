<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

interface OptionContainerInterface
{
    /**
     * @return OptionItemInterface[]
     */
    public function getOptions();
}
