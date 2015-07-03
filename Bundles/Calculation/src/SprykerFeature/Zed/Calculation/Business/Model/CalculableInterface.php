<?php

namespace SprykerFeature\Zed\Calculation\Business\Model;

use Generated\Shared\Calculation\CalculableContainerInterface;

interface CalculableInterface
{

    /**
     * @return CalculableContainerInterface
     */
    public function getCalculableObject();

}
