<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Checkout\Form;

use Spryker\Yves\Checkout\CheckoutDependencyProvider;
use Spryker\Yves\Checkout\Form\Provider\FilterableSubFormProvider;
use Spryker\Yves\Checkout\Form\Provider\SubFormDataProviders;
use Spryker\Yves\Kernel\AbstractFactory;

class FormFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\Checkout\Form\Provider\FilterableSubFormProvider
     */
    protected function createPaymentMethodSubFormProvider()
    {
        return new FilterableSubFormProvider(
            $this->getPaymentMethodSubForms(),
            $this->getMethodFormFilters()
        );
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection
     */
    public function getPaymentMethodSubForms()
    {
        return $this->getProvidedDependency(CheckoutDependencyProvider::PAYMENT_SUB_FORMS);
    }

    /**
     * @return \Spryker\Yves\Checkout\Dependency\Plugin\Form\SubFormFilterPluginInterface[]
     */
    public function getMethodFormFilters()
    {
        return $this->getProvidedDependency(CheckoutDependencyProvider::PLUGIN_PAYMENT_FILTERS);
    }

    /**
     * @param \Spryker\Yves\Checkout\Form\Provider\FilterableSubFormProvider $subFormProvider
     *
     * @return \Spryker\Yves\Checkout\Form\Provider\SubFormDataProviders
     */
    protected function createSubFormDataProvider(FilterableSubFormProvider $subFormProvider)
    {
        return new SubFormDataProviders($subFormProvider);
    }
}
