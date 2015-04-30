<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

interface OptionContainerInterface
{
    /**
     * @return OptionItemInterface[]
     */
    public function getOptions();

    /**
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options = []);
}
