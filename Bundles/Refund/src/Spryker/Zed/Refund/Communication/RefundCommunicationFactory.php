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
class RefundCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @param OrderTransfer $orderTransfer
     * @param RefundFacade $facadeRefund
     *
     * @return RefundForm
     */
    public function createRefundForm(OrderTransfer $orderTransfer, RefundFacade $facadeRefund)
    {
        $paymentDataPlugin = $this->getConfig()->getPaymentDataPlugin();

        $form = new RefundForm($facadeRefund, $orderTransfer, $paymentDataPlugin);

        return $this->createForm($form);
    }

    /**
     * @param RefundFacade $refundFacade
     * @return RefundTable
     */
    public function createRefundTable(RefundFacade $refundFacade)
    {
        $refundQuery = $this->getQueryContainer()->queryRefund();

        return new RefundTable(
            $refundQuery,
            $refundFacade,
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
