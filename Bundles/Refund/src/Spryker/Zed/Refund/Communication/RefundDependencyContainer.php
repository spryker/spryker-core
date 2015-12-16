<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Refund\Communication;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Shared\Library\Context;
use Spryker\Shared\Library\DateFormatter;
use Spryker\Zed\Refund\Business\RefundFacade;
use Spryker\Zed\Refund\Communication\Form\RefundForm;
use Spryker\Zed\Refund\Communication\Table\RefundTable;
use Spryker\Zed\Refund\Persistence\RefundQueryContainer;
use Spryker\Zed\Refund\RefundConfig;
use Spryker\Zed\Refund\RefundDependencyProvider;
use Spryker\Zed\Sales\Persistence\SalesQueryContainer;

/**
 * @method RefundQueryContainer getQueryContainer()
 * @method RefundConfig getConfig()
 */
class RefundDependencyContainer extends AbstractCommunicationFactory
{

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return RefundForm
     */
    public function createRefundForm(OrderTransfer $orderTransfer)
    {
        $refundFacade = $this->getRefundFacade();
        $paymentDataPlugin = $this->getConfig()->getPaymentDataPlugin();

        return new RefundForm($refundFacade, $orderTransfer, $paymentDataPlugin);
    }

    /**
     * @return RefundTable
     */
    public function createRefundTable()
    {
        $refundQuery = $this->getQueryContainer()->queryRefund();

        return new RefundTable(
            $refundQuery,
            $this->getRefundFacade(),
            $this->createDateFormatter()
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

    /**
     * @throws \Exception
     *
     * @return DateFormatter
     */
    protected function createDateFormatter()
    {
        $dateFormatter = new DateFormatter(Context::getInstance());

        return $dateFormatter;
    }

}
