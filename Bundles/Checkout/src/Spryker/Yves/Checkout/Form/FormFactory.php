<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Checkout\Form;

use Spryker\Yves\Checkout\CheckoutDependencyProvider;
use Spryker\Yves\Checkout\DataContainer\DataContainer;
use Spryker\Yves\Checkout\Form\Filter\SubFormFilter;
use Spryker\Yves\Checkout\Form\Provider\SubFormDataProviders;
use Spryker\Yves\Checkout\Form\Steps\PaymentForm;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
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
        $subFormDataProvider = $this->createSubFormDataProvider();

        return $this->createSubFormCollection(PaymentForm::class, $subFormDataProvider);
    }

    /**
     * @return \Spryker\Yves\Checkout\Dependency\Plugin\Form\SubFormFilterPluginInterface[]
     */
    public function getMethodFormFilters()
    {
        return $this->getProvidedDependency(CheckoutDependencyProvider::PLUGIN_PAYMENT_FILTERS);
    }

    /**
     * @return \Spryker\Yves\Checkout\Form\Filter\SubFormFilter
     */
    public function createPaymentSubFormFilter()
    {
        return new SubFormFilter(
            $this->getPaymentMethodSubFormPluginCollection(),
            $this->getMethodFormFilters(),
            $this->createDataContainer()
        );
    }

    /**
     * @param string $formType
     * @param \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface $dataProvider
     *
     * @return \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface
     */
    protected function createSubFormCollection($formType, StepEngineFormDataProviderInterface $dataProvider)
    {
        return new FormCollectionHandler([$formType], $this->getFormFactory(), $dataProvider);
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    protected function getFormFactory()
    {
        return $this->getProvidedDependency(CheckoutDependencyProvider::FORM_FACTORY);
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    protected function createSubFormDataProvider()
    {
        return new SubFormDataProviders(
            $this->createPaymentSubFormFilter()
                ->getSubForms()
        );
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
