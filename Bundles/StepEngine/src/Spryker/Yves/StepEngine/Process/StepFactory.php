<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngine\Process;

use Spryker\Client\Cart\CartClientInterface;
use Spryker\Yves\StepEngine\CheckoutDependencyProvider;
use Spryker\Yves\Kernel\AbstractFactory;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StepFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\CheckoutStepHandlerPluginCollection
     */
    public function createPaymentMethodHandler()
    {
        return $this->getProvidedDependency(CheckoutDependencyProvider::PAYMENT_METHOD_HANDLER);
    }

    /**
     * @param array $steps
     * @param \Symfony\Component\Routing\Generator\UrlGeneratorInterface $urlGenerator
     * @param \Spryker\Client\Cart\CartClientInterface $cartClient
     * @param string $errorRoute
     *
     * @return \Spryker\Yves\StepEngine\Process\StepProcess
     */
    public function createStepProcess(array $steps, UrlGeneratorInterface $urlGenerator, CartClientInterface $cartClient, $errorRoute)
    {
        return new StepProcess($steps, $urlGenerator, $cartClient, $errorRoute);
    }

}
