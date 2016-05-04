<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Checkout\Process;

use Spryker\Yves\Checkout\CheckoutDependencyProvider;
use Spryker\Yves\Kernel\AbstractFactory;

class StepFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Yves\Checkout\Dependency\Plugin\CheckoutStepHandlerPluginCollection
     */
    public function createPaymentPlugins()
    {
        return $this->getProvidedDependency(CheckoutDependencyProvider::PAYMENT_METHOD_HANDLER);
    }

}
