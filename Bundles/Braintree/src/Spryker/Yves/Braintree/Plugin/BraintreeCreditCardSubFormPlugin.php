<?php

namespace Spryker\Yves\Braintree\Plugin;

use Spryker\Yves\Checkout\Dependency\Plugin\CheckoutSubFormPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\Braintree\BraintreeFactory getFactory()
 */
class BraintreeCreditCardSubFormPlugin extends AbstractPlugin implements CheckoutSubFormPluginInterface
{

    /**
     * @return \Spryker\Yves\Braintree\Form\PayPalSubForm
     */
    public function createSubForm()
    {
        return $this->getFactory()->createCreditCardForm();
    }

    /**
     * @return \Spryker\Yves\Checkout\Dependency\DataProvider\DataProviderInterface
     */
    public function createSubFormDataProvider()
    {
        return $this->getFactory()->createCreditCardFormDataProvider();
    }

}
