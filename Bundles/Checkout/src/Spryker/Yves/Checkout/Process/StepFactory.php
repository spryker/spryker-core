<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Checkout\Process;

use Spryker\Yves\Checkout\CheckoutDependencyProvider;
use Spryker\Yves\Checkout\DataContainer\DataContainer;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\StepEngine\Process\StepCollectionInterface;
use Spryker\Yves\StepEngine\Process\StepEngine;

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
     * @param \Spryker\Yves\StepEngine\Process\StepCollectionInterface $stepCollection
     *
     * @return \Spryker\Yves\StepEngine\Process\StepEngine
     */
    public function createStepEngine(StepCollectionInterface $stepCollection)
    {
        return new StepEngine($stepCollection, $this->createDataContainer());
    }

    /**
     * @return \Spryker\Yves\Checkout\DataContainer\DataContainer
     */
    public function createDataContainer()
    {
        return new DataContainer($this->getQuoteClient());
    }

    /**
     * @return \Spryker\Yves\Checkout\Dependency\Client\CheckoutToQuoteInterface
     */
    protected function getQuoteClient()
    {
        return $this->getProvidedDependency(CheckoutDependencyProvider::CLIENT_QUOTE);
    }
}
