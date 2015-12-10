<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Payolution\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use SprykerFeature\Zed\Payolution\Communication\Table\Payments;
use SprykerFeature\Zed\Payolution\Communication\Table\RequestLog;
use SprykerFeature\Zed\Payolution\Communication\Table\StatusLog;
use SprykerFeature\Zed\Payolution\PayolutionConfig;
use SprykerFeature\Zed\Payolution\Persistence\PayolutionQueryContainerInterface;
use SprykerFeature\Zed\Payolution\PayolutionDependencyProvider;
use SprykerFeature\Zed\Mail\Business\MailFacade;

/**
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

        return new Payments($paymentPayolutionQuery);
    }

    /**
     * @param int $idPayment
     *
     * @return RequestLog
     */
    public function createRequestLogTable($idPayment)
    {
        $requestLogQuery = $this->getQueryContainer()->queryTransactionRequestLogByPaymentId($idPayment);

        return new RequestLog($requestLogQuery, $idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return StatusLog
     */
    public function createStatusLogTable($idPayment)
    {
        $statusLogQuery= $this->getQueryContainer()->queryTransactionStatusLogByPaymentId($idPayment);

        return new StatusLog($statusLogQuery, $idPayment);
    }

    /**
     * @return MailFacade
     */
    public function getMailFacade()
    {
        return $this->getProvidedDependency(PayolutionDependencyProvider::FACADE_MAIL);
    }

    /**
     * @return GlossaryFacade
     */
    public function getGlossaryFacade()
    {
        return $this->getProvidedDependency(PayolutionDependencyProvider::FACADE_GLOSSARY);
    }

}
