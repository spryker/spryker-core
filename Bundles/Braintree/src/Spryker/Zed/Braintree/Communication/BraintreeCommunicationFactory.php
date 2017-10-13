<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Communication;

use Spryker\Zed\Braintree\Communication\Table\Payments;
use Spryker\Zed\Braintree\Communication\Table\RequestLog;
use Spryker\Zed\Braintree\Communication\Table\StatusLog;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Braintree\Persistence\BraintreeQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Braintree\BraintreeConfig getConfig()
 */
class BraintreeCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Braintree\Communication\Table\Payments
     */
    public function createPaymentsTable()
    {
        $paymentBraintreeQuery = $this->getQueryContainer()->queryPayments();

        return new Payments($paymentBraintreeQuery);
    }

    /**
     * @param int $idPayment
     *
     * @return \Spryker\Zed\Braintree\Communication\Table\RequestLog
     */
    public function createRequestLogTable($idPayment)
    {
        $requestLogQuery = $this->getQueryContainer()->queryTransactionRequestLogByPaymentId($idPayment);

        return new RequestLog($requestLogQuery, $idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return \Spryker\Zed\Braintree\Communication\Table\StatusLog
     */
    public function createStatusLogTable($idPayment)
    {
        $statusLogQuery = $this->getQueryContainer()->queryTransactionStatusLogByPaymentId($idPayment);

        return new StatusLog($statusLogQuery, $idPayment);
    }
}
