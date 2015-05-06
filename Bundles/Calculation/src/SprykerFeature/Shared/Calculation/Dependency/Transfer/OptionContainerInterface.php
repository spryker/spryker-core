<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

/**
 * @TODO check if return types are correct here. Set array and expect a OptionItemInterface when use get cant work
 */
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
    public function setOptions(array $options);
}
