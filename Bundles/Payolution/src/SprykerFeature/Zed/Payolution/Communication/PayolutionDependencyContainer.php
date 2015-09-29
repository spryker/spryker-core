<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Payolution\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\PayolutionCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Payolution\Communication\Table\Payments;
use SprykerFeature\Zed\Payolution\Communication\Table\RequestLog;
use SprykerFeature\Zed\Payolution\Communication\Table\StatusLog;
use SprykerFeature\Zed\Payolution\PayolutionConfig;
use SprykerFeature\Zed\Payolution\Persistence\PayolutionQueryContainerInterface;

/**
 * @method PayolutionCommunication getFactory()
 * @method PayolutionQueryContainerInterface getQueryContainer()
 * @method PayolutionConfig getConfig()
 */
class PayolutionDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return Payments
     */
    public function createPaymentsTable()
    {
        $paymentPayolutionQuery = $this->getQueryContainer()->queryPayments();

        return $this->getFactory()->createTablePayments($paymentPayolutionQuery);
    }

    /**
     * @param int $idPayment
     *
     * @return RequestLog
     */
    public function createRequestLogTable($idPayment)
    {
        $requestLogQuery = $this->getQueryContainer()->queryTransactionRequestLogByPaymentId($idPayment);

        return $this->getFactory()->createTableRequestLog($requestLogQuery, $idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return StatusLog
     */
    public function createStatusLogTable($idPayment)
    {
        $statusLogQuery= $this->getQueryContainer()->queryTransactionStatusLogByPaymentId($idPayment);

        return $this->getFactory()->createTableStatusLog($statusLogQuery, $idPayment);
    }

}
