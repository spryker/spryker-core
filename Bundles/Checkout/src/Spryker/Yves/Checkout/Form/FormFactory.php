<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Checkout\Form;

use Pyz\Yves\Checkout\Form\Steps\PaymentForm;
use Spryker\Yves\Checkout\CheckoutDependencyProvider;
use Spryker\Yves\Checkout\DataContainer\DataContainer;
use Spryker\Yves\Checkout\Form\Provider\FilterableSubFormProvider;
use Spryker\Yves\Checkout\Form\Provider\SubFormDataProviders;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;
use Spryker\Yves\StepEngine\Form\FormCollectionHandler;

class FormFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection
     */
    public function getPaymentMethodSubFormPluginCollection()
    {
        return $this->getProvidedDependency(CheckoutDependencyProvider::PAYMENT_SUB_FORMS);
    }

    /**
     * @deprecated Use getPaymentMethodSubFormPluginCollection instead.
     * Will be removed in the next major release.
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection
     */
    public function getPaymentMethodSubForms()
    {
        return $this->getPaymentMethodSubFormPluginCollection();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface
     */
    public function createPaymentFormCollection()
    {
        $subFormCollection = $this->createPaymentSubFormFilter()->getSubForms();
        $paymentFormType = $this->createPaymentForm($subFormCollection);
        $subFormDataProvider = $this->createSubFormDataProvider($subFormCollection);

        return $this->createSubFormCollection($paymentFormType, $subFormDataProvider);
    }

    /**
     * @return \Spryker\Yves\Checkout\Dependency\Plugin\Form\SubFormFilterPluginInterface[]
     */
    public function getMethodFormFilters()
    {
        return $this->getProvidedDependency(CheckoutDependencyProvider::PLUGIN_PAYMENT_FILTERS);
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection $subForms
     *
     * @return \Pyz\Yves\Checkout\Form\Steps\PaymentForm
     */
    protected function createPaymentForm(SubFormPluginCollection $subForms)
    {
        return new PaymentForm($subForms);
    }

    /**
     * @param \Symfony\Component\Form\FormTypeInterface $formType
     * @param \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface $dataProvider
     *
     * @return \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface
     */
    protected function createSubFormCollection($formType, StepEngineFormDataProviderInterface $dataProvider)
    {
        return new FormCollectionHandler([$formType], $this->getFormFactory(), $dataProvider);
    }

    /**
     * @return \Spryker\Yves\Checkout\Form\Provider\FilterableSubFormProvider
     */
    public function createPaymentSubFormFilter()
    {
        return new FilterableSubFormProvider(
            $this->getPaymentMethodSubFormPluginCollection(),
            $this->getMethodFormFilters(),
            $this->createDataContainer()
        );
    }

    protected function createSubFormDataProvider($subFormProvider)
    {
        return new SubFormDataProviders($subFormProvider);
    }

    /**
     * @return \Spryker\Yves\Checkout\DataContainer\DataContainer
     */
    protected function createDataContainer()
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
