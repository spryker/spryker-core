<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Checkout\Form;

use Spryker\Yves\Checkout\CheckoutDependencyProvider;
use Spryker\Yves\Kernel\AbstractFactory;

class FormFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Yves\Checkout\Dependency\Plugin\CheckoutSubFormPluginCollection
     */
    public function createPaymentMethodsSubForms()
    {
        return $this->getProvidedDependency(CheckoutDependencyProvider::PAYMENT_SUB_FORMS);
    }

}
