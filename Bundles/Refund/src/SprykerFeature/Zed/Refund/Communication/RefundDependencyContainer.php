<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Refund\Communication;

use Generated\Shared\Sales\OrderInterface;
use Generated\Zed\Ide\FactoryAutoCompletion\RefundCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Shared\Library\Context;
use SprykerFeature\Shared\Library\DateFormatter;
use SprykerFeature\Zed\Refund\Business\RefundFacade;
use SprykerFeature\Zed\Refund\Communication\Form\RefundForm;
use SprykerFeature\Zed\Refund\Communication\Table\RefundsTable;
use SprykerFeature\Zed\Refund\Persistence\RefundQueryContainer;
use SprykerFeature\Zed\Refund\RefundDependencyProvider;
use SprykerFeature\Zed\Sales\Persistence\SalesQueryContainer;

/**
 * @method RefundCommunication getFactory()
 * @method RefundQueryContainer getQueryContainer()
 */
class RefundDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return RefundForm
     */
    public function createRefundForm(OrderInterface $orderTransfer)
    {
        $refundFacade = $this->getRefundFacade();
        return $this->getFactory()->createFormRefundForm($refundFacade, $orderTransfer);
    }

    /**
     * @return RefundsTable
     */
    public function createRefundsTable()
    {
        $refundQuery = $this->getQueryContainer()->queryRefund();

        return $this->getFactory()->createTableRefundsTable(
            $refundQuery,
            $this->getRefundFacade(),
            new DateFormatter(Context::getInstance())
        );
    }

    /**
     * @return SalesQueryContainer
     */
    public function getSalesQueryContainer()
    {
        return $this->getProvidedDependency(RefundDependencyProvider::QUERY_CONTAINER_SALES);
    }

    /**
     * @return RefundFacade
     */
    protected function getRefundFacade()
    {
        return $this->getProvidedDependency(RefundDependencyProvider::FACADE_REFUND);
    }

}
