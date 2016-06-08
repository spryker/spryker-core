<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\DummyPayment;

use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Yves\DummyPayment\Form\CreditCardSubForm;
use Spryker\Yves\DummyPayment\Form\DataProvider\DummyPaymentCreditCardFormDataProvider;
use Spryker\Yves\DummyPayment\Form\DataProvider\DummyPaymentInvoiceFormDataProvider;
use Spryker\Yves\DummyPayment\Form\InvoiceSubForm;
use Spryker\Yves\DummyPayment\Handler\DummyPaymentHandler;
use Spryker\Yves\DummyPayment\Plugin\DummyPaymentCreditCardSubFormPlugin;
use Spryker\Yves\DummyPayment\Plugin\DummyPaymentHandlerPlugin;
use Spryker\Yves\DummyPayment\Plugin\DummyPaymentInvoiceSubFormPlugin;
use Spryker\Yves\Kernel\AbstractFactory;

class DummyPaymentFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Yves\DummyPayment\Form\CreditCardSubForm
     */
    public function createCreditCardForm()
    {
        return new CreditCardSubForm();
    }

    /**
     * @return \Spryker\Yves\DummyPayment\Form\DataProvider\DummyPaymentCreditCardFormDataProvider
     */
    public function createCreditCardFormDataProvider()
    {
        return new DummyPaymentCreditCardFormDataProvider();
    }

    /**
     * @return \Spryker\Yves\DummyPayment\Plugin\DummyPaymentCreditCardSubFormPlugin
     */
    public function createCreditCardSubFormPlugin()
    {
        return new DummyPaymentCreditCardSubFormPlugin();
    }

    /**
     * @return \Spryker\Yves\DummyPayment\Form\InvoiceSubForm
     */
    public function createInvoiceForm()
    {
        return new InvoiceSubForm();
    }

    /**
     * @return \Spryker\Yves\DummyPayment\Form\DataProvider\DummyPaymentInvoiceFormDataProvider
     */
    public function createInvoiceFormDataProvider()
    {
        return new DummyPaymentInvoiceFormDataProvider();
    }

    /**
     * @return \Spryker\Yves\DummyPayment\Plugin\DummyPaymentInvoiceSubFormPlugin
     */
    public function createInvoiceSubFormPlugin()
    {
        return new DummyPaymentInvoiceSubFormPlugin();
    }

    /**
     * @return \Spryker\Yves\DummyPayment\Handler\DummyPaymentHandler
     */
    public function createDummyPaymentHandler()
    {
        return new DummyPaymentHandler($this->createCurrencyManager());
    }

    /**
     * @return \Spryker\Yves\DummyPayment\Plugin\DummyPaymentHandlerPlugin
     */
    public function createDummyPaymentHandlerPlugin()
    {
        return new DummyPaymentHandlerPlugin();
    }

    /**
     * @return \Spryker\Shared\Library\Currency\CurrencyManager
     */
    protected function createCurrencyManager()
    {
        return CurrencyManager::getInstance();
    }

}
