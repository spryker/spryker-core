<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Payolution\Service;

use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Payolution\Service\Zed\PayolutionStub;
use SprykerFeature\Client\Payolution\Service\Zed\PayolutionStubInterface;
use Generated\Client\Ide\FactoryAutoCompletion\PayolutionService;

/**
 * @method PayolutionService getFactory()
 */
class PayolutionDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @return PayolutionStubInterface
     */
    public function createPayolutionStub()
    {
        return new PayolutionStub($this->createZedRequestClient());
    }

}
