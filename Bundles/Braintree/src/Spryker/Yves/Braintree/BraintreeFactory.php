<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Braintree;

use Spryker\Yves\Braintree\Form\CreditCardSubForm;
use Spryker\Yves\Braintree\Form\DataProvider\CreditCardDataProvider;
use Spryker\Yves\Braintree\Form\DataProvider\PayPalDataProvider;
use Spryker\Yves\Braintree\Form\PayPalSubForm;
use Spryker\Yves\Braintree\Handler\BraintreeHandler;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\Braintree\BraintreeClientInterface getClient()
 */
class BraintreeFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\Braintree\Form\PayPalSubForm
     */
    public function createPayPalForm()
    {
        return new PayPalSubForm();
    }

    /**
     * @return \Spryker\Yves\Braintree\Form\CreditCardSubForm
     */
    public function createCreditCardForm()
    {
        return new CreditCardSubForm();
    }

    /**
     * @return \Spryker\Yves\Braintree\Form\DataProvider\PayPalDataProvider
     */
    public function createPayPalFormDataProvider()
    {
        return new PayPalDataProvider();
    }

    /**
     * @return \Spryker\Yves\Braintree\Form\DataProvider\CreditCardDataProvider
     */
    public function createCreditCardFormDataProvider()
    {
        return new CreditCardDataProvider();
    }

    /**
     * @return \Spryker\Yves\Braintree\Handler\BraintreeHandler
     */
    public function createBraintreeHandler()
    {
        return new BraintreeHandler($this->getClient(), $this->getCurrencyPlugin());
    }

    /**
     * @return \Spryker\Yves\Currency\Plugin\CurrencyPluginInterface
     */
    protected function getCurrencyPlugin()
    {
        return $this->getProvidedDependency(BraintreeDependencyProvider::PLUGIN_CURRENCY);
    }
}
