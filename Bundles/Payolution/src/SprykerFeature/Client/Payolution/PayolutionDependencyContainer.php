<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Payolution;

use SprykerEngine\Client\Kernel\AbstractDependencyContainer;
use SprykerFeature\Client\Payolution\Zed\PayolutionStub;
use SprykerFeature\Client\Payolution\Zed\PayolutionStubInterface;
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
