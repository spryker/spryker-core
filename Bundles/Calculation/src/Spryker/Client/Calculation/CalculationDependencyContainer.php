<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Calculation\Service;

use Generated\Client\Ide\FactoryAutoCompletion\CalculationService;
use Spryker\Client\Kernel\AbstractDependencyContainer;
use Spryker\Client\Calculation\Zed\CalculationStub;

/**
 * @method CalculationService getFactory()
 */
class CalculationDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return CalculationStub
     */
    public function createZedStub()
    {
        return $this->getFactory()->createZedCalculationStub($this->createZedRequestClient());
    }
}
