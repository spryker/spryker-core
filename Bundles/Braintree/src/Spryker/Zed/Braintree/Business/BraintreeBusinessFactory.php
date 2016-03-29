<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business;

use Braintree;
use Spryker\Zed\Braintree\Business\Api\Adapter\BraintreeAdapter;
use Spryker\Zed\Braintree\Business\Api\Converter\Converter;
use Spryker\Zed\Braintree\Business\Log\TransactionStatusLog;
use Spryker\Zed\Braintree\Business\Order\Saver;
use Spryker\Zed\Braintree\Business\Payment\Handler\Calculation\Calculation;
use Spryker\Zed\Braintree\Business\Payment\Handler\Transaction\Transaction;
use Spryker\Zed\Braintree\Business\Payment\Method\PayPal\PayPal;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Braintree\Persistence\BraintreeQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Braintree\BraintreeConfig getConfig()
 */
class BraintreeBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Braintree\Business\Payment\Handler\Transaction\TransactionInterface
     */
    public function createPaymentTransactionHandler()
    {
        $paymentTransactionHandler = new Transaction(
            $this->createAdapter(),
            $this->createConverter(),
            $this->getQueryContainer(),
            $this->getConfig()
        );

        $paymentTransactionHandler->registerMethodMapper(
            $this->createPayPal()
        );

        return $paymentTransactionHandler;
    }

    /**
     * @return \Spryker\Zed\Braintree\Business\Payment\Handler\Calculation\CalculationInterface
     */
    public function createPaymentCalculationHandler()
    {
        $paymentCalculationHandler = new Calculation(
            $this->createAdapter(),
            $this->createConverter(),
            $this->getConfig()
        );

        $paymentCalculationHandler->registerMethodMapper(
            $this->createPayPal()
        );

        return $paymentCalculationHandler;
    }

    /**
     * @return \Spryker\Zed\Payolution\Business\Api\Adapter\AdapterInterface
     */
    protected function createAdapter()
    {
        return new BraintreeAdapter();
    }

    /**
     * @return \Spryker\Zed\Braintree\Business\Order\SaverInterface
     */
    public function createOrderSaver()
    {
        return new Saver();
    }

    /**
     * @return \Spryker\Zed\Braintree\Business\Log\TransactionStatusLogInterface
     */
    public function createTransactionStatusLog()
    {
        return new TransactionStatusLog($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Braintree\Business\Payment\Method\PayPal\PayPalInterface
     */
    protected function createPayPal()
    {
        return new PayPal($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Payolution\Business\Api\Converter\ConverterInterface
     */
    private function createConverter()
    {
        return new Converter();
    }

}
