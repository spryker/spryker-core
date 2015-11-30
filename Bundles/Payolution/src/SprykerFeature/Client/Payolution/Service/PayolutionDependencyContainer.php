<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Payolution\Service;

use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Payolution\PayolutionDependencyProvider;
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
    public function createZedStub()
    {
        $zedStub = $this->getProvidedDependency(PayolutionDependencyProvider::SERVICE_ZED);
        $payolutionStub = $this->getFactory()->createZedPayolutionStub($zedStub);

        return $payolutionStub;
    }

}
