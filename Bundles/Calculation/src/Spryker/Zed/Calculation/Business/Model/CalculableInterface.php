<?php

namespace Spryker\Zed\Calculation\Business\Model;

use Generated\Shared\Transfer\CalculableContainerTransfer;

interface CalculableInterface
{

    /**
     * @return CalculableContainerTransfer
     */
    public function getCalculableObject();

}
