<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business;

use Spryker\Zed\Braintree\Business\Api\Adapter\BraintreeAdapter;
use Spryker\Zed\Braintree\Business\Hook\PostSaveHook;
use Spryker\Zed\Braintree\Business\Log\TransactionStatusLog;
use Spryker\Zed\Braintree\Business\Order\Saver;
use Spryker\Zed\Braintree\Business\Payment\Handler\Transaction\Transaction;
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
            $this->getQueryContainer(),
            $this->getConfig()
        );

        return $paymentTransactionHandler;
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
     * @return \Spryker\Zed\Braintree\Business\Hook\PostSaveHookInterface
     */
    public function createPostSaveHook()
    {
        return new PostSaveHook(
            $this->getQueryContainer()
        );
    }

}
