<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Payolution;

use Spryker\Client\Kernel\AbstractDependencyContainer;
use Spryker\Client\Payolution\Zed\PayolutionStub;
use Spryker\Client\Payolution\Zed\PayolutionStubInterface;
use Generated\Client\Ide\FactoryAutoCompletion\PayolutionService;

/**
 * @method PayolutionService getFactory()
 */
class PayolutionDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return PayolutionStubInterface
     */
    public function createPayolutionStub()
    {
        return new PayolutionStub($this->createZedRequestClient());
    }

}
