<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Checkout\Process;

use Spryker\Yves\Checkout\CheckoutDependencyProvider;
use Spryker\Yves\Checkout\DataContainer\DataContainer;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\StepEngine\Process\StepEngine;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StepFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection
     */
    public function createPaymentMethodHandler()
    {
        return $this->getProvidedDependency(CheckoutDependencyProvider::PAYMENT_METHOD_HANDLER);
    }

    /**
     * @param array $steps
     * @param \Symfony\Component\Routing\Generator\UrlGeneratorInterface $urlGenerator
     * @param string $errorRoute
     *
     * @return \Spryker\Yves\StepEngine\Process\StepEngine
     */
    public function createStepProcess(array $steps, UrlGeneratorInterface $urlGenerator, $errorRoute)
    {
        return new StepEngine($steps, $this->createDataContainer(), $urlGenerator, $errorRoute);
    }

    /**
     * @return \Spryker\Yves\Checkout\DataContainer\DataContainer
     */
    protected function createDataContainer()
    {
        return new DataContainer($this->getCartClient());
    }

    /**
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return \Spryker\Client\Cart\CartClientInterface
     */
    protected function getCartClient()
    {
        return $this->getProvidedDependency(CheckoutDependencyProvider::CLIENT_CART);
    }

}
