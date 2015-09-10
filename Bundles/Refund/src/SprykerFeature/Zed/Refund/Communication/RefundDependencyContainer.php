<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Refund\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\RefundCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Refund\Communication\Form\RefundForm;
use SprykerFeature\Zed\Refund\Communication\Plugin\RefundCalculationPlugin;
use SprykerFeature\Zed\Refund\Persistence\RefundQueryContainer;
use SprykerFeature\Zed\Refund\RefundDependencyProvider;
use SprykerFeature\Zed\Tax\Persistence\TaxQueryContainerInterface;

/**
 * @method RefundCommunication getFactory()
 * @method RefundQueryContainer getQueryContainer()
 */
class RefundDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return RefundForm
     */
    public function createRefundForm()
    {
        //$refundQuery = $this->getQueryContainer()->queryRefundByIdRefund();

        return $this->getFactory()->createFormRefundForm();
    }

}
