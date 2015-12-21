<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Calculation;

use Spryker\Client\Calculation\Zed\CalculationStub;
use Spryker\Client\Kernel\AbstractFactory;

class CalculationFactory extends AbstractFactory
{

    /**
     * @return CalculationStub
     */
    public function createZedStub()
    {
        return new CalculationStub($this->createZedRequestClient());
    }

}
