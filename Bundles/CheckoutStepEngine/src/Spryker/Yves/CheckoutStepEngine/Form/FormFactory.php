<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CheckoutStepEngine\Form;

use Spryker\Yves\CheckoutStepEngine\CheckoutDependencyProvider;
use Spryker\Yves\Kernel\AbstractFactory;

class FormFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Yves\CheckoutStepEngine\Dependency\Plugin\Form\CheckoutSubFormPluginCollection
     */
    public function createPaymentMethodSubForms()
    {
        return $this->getProvidedDependency(CheckoutDependencyProvider::PAYMENT_SUB_FORMS);
    }

}
