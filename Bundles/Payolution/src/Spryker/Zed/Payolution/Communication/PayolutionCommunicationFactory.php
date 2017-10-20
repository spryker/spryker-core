<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payolution\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Payolution\Communication\Table\Payments;
use Spryker\Zed\Payolution\Communication\Table\RequestLog;
use Spryker\Zed\Payolution\Communication\Table\StatusLog;
use Spryker\Zed\Payolution\PayolutionDependencyProvider;

/**
 * @method \Spryker\Zed\Payolution\Persistence\PayolutionQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Payolution\PayolutionConfig getConfig()
 */
class PayolutionCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Payolution\Communication\Table\Payments
     */
    public function createPaymentsTable()
    {
        $paymentPayolutionQuery = $this->getQueryContainer()->queryPayments();

        return new Payments($paymentPayolutionQuery);
    }

    /**
     * @param int $idPayment
     *
     * @return \Spryker\Zed\Payolution\Communication\Table\RequestLog
     */
    public function createRequestLogTable($idPayment)
    {
        $requestLogQuery = $this->getQueryContainer()->queryTransactionRequestLogByPaymentId($idPayment);

        return new RequestLog($requestLogQuery, $idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return \Spryker\Zed\Payolution\Communication\Table\StatusLog
     */
    public function createStatusLogTable($idPayment)
    {
        $statusLogQuery = $this->getQueryContainer()->queryTransactionStatusLogByPaymentId($idPayment);

        return new StatusLog($statusLogQuery, $idPayment);
    }

    /**
     * @return \Spryker\Zed\Payolution\Dependency\Facade\PayolutionToMailInterface
     */
    public function getMailFacade()
    {
        return $this->getProvidedDependency(PayolutionDependencyProvider::FACADE_MAIL);
    }

    /**
     * @return \Spryker\Zed\Payolution\Dependency\Facade\PayolutionToGlossaryInterface
     */
    public function getGlossaryFacade()
    {
        return $this->getProvidedDependency(PayolutionDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\Payolution\Dependency\Facade\PayolutionToSalesInterface
     */
    public function getSalesFacade()
    {
        return $this->getProvidedDependency(PayolutionDependencyProvider::FACADE_SALES);
    }
}
