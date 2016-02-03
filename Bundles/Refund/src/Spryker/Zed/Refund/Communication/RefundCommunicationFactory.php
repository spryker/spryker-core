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
use Spryker\Zed\Refund\RefundDependencyProvider;

/**
 * @method \Spryker\Zed\Refund\Persistence\RefundQueryContainer getQueryContainer()
 * @method \Spryker\Zed\Refund\RefundConfig getConfig()
 */
class RefundCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Spryker\Zed\Refund\Business\RefundFacade $facadeRefund
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createRefundForm(OrderTransfer $orderTransfer, RefundFacade $facadeRefund)
    {
        $paymentDataPlugin = $this->getConfig()->getPaymentDataPlugin();

        $form = new RefundForm($facadeRefund, $orderTransfer, $paymentDataPlugin);

        return $this->createForm($form);
    }

    /**
     * @param \Spryker\Zed\Refund\Business\RefundFacade $refundFacade
     *
     * @return \Spryker\Zed\Refund\Communication\Table\RefundTable
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
     * @return \Spryker\Zed\Sales\Persistence\SalesQueryContainer
     */
    public function getSalesQueryContainer()
    {
        return $this->getProvidedDependency(RefundDependencyProvider::QUERY_CONTAINER_SALES);
    }

    /**
     * @throws \Exception
     *
     * @return \Spryker\Shared\Library\DateFormatter
     */
    protected function createDateFormatter()
    {
        $dateFormatter = new DateFormatter(Context::getInstance());

        return $dateFormatter;
    }

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return \Spryker\Zed\Payone\Business\PayoneFacade
     */
    public function getPayoneFacade()
    {
        return $this->getProvidedDependency(RefundDependencyProvider::FACADE_PAYONE);
    }

}
