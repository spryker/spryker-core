<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Communication;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Library\Context;
use Spryker\Shared\Library\DateFormatter;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
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
     * @param \Spryker\Zed\Refund\Business\RefundFacade $refundFacade
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createRefundForm(OrderTransfer $orderTransfer, RefundFacade $refundFacade)
    {
        $paymentDataPlugin = $this->getConfig()->getPaymentDataPlugin();

        $form = new RefundForm($refundFacade, $orderTransfer, $paymentDataPlugin);

        return $this->getFormFactory()->create($form);
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
            $this->getProvidedDependency(RefundDependencyProvider::SERVICE_DATE_FORMATTER)
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
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return \Spryker\Zed\Payone\Business\PayoneFacade
     */
    public function getPayoneFacade()
    {
        return $this->getProvidedDependency(RefundDependencyProvider::FACADE_PAYONE);
    }

    /**
     * @return \Spryker\Zed\Refund\Dependency\Facade\RefundToSalesAggregatorInterface
     */
    public function getSalesAggregatorFacade()
    {
        return $this->getProvidedDependency(RefundDependencyProvider::FACADE_SALES_AGGREGATOR);
    }

}
