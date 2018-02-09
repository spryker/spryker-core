<?php


namespace Spryker\Yves\Checkout;


use Spryker\Yves\Checkout\Form\FormFactory;
use Spryker\Yves\Kernel\AbstractFactory;

class CheckoutFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection
     */
    public function createPaymentMethodSubForms()
    {
        return $this->getProvidedDependency(CheckoutDependencyProvider::PAYMENT_SUB_FORMS);
    }

    /**
     * @return \Spryker\Yves\Checkout\Form\FormFactory
     */
    public function createCheckoutFormFactory()
    {
        return new FormFactory();
    }
}