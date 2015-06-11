<?php

namespace SprykerFeature\Zed\Checkout\Communication\Controller;

use Generated\Shared\Transfer\OrderItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Shared\ZedRequest\Client\RequestInterface;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractSdkController;
use SprykerFeature\Zed\Calculation\Business\CalculationFacade;
use SprykerFeature\Zed\Checkout\Business\CheckoutFacade;

/**
 * @method CheckoutFacade getFacade()
 */
class SdkController extends AbstractSdkController
{

    const MESSAGE_KEY = 'message';
    const DATA_KEY = 'data';

    /**
     * @param OrderTransfer $transferOrder
     *
     * @return OrderTransfer
     */
    public function recalculateAction(OrderTransfer $transferOrder)
    {
        return $this->getCalculationFacade()->recalculate($transferOrder);
    }

    /**
     * @param OrderTransfer $transferOrder
     * @param RequestInterface $requestTransfer
     *
     * @return OrderTransfer
     */
    public function saveOrderAction(OrderTransfer $transferOrder, RequestInterface $requestTransfer)
    {
        $logContext = [];
        $logContext["module"] = 'SprykerFeature\Zed\Checkout\Communication\Controller';// @todo FIXME
        $logContext["controller"] = "SdkController";
        $logContext["action"] = "saveOrderAction";
        $logContext["params"] = ["ToBeDone"];// @todo FIXME

        $componentResult = $this->getFacade()->saveOrder($transferOrder, $requestTransfer, $logContext);

        if (!$componentResult->isSuccess()) {
            $this->setSuccess(false);
            foreach ($componentResult->getErrors() as $error) {
                $this->addErrorMessage($error);
            }

            // on error recalculate to be sane
            return $this->getCalculationFacade()->recalculate($transferOrder);
        } else {
            return $componentResult->getTransfer();
        }
    }

    /**
     * @return CalculationFacade
     */
    public function getCalculationFacade()
    {
        return $this->getDependencyContainer()->createCalculationFacade();
    }

    /**
     * @return CheckoutFacade
     */
    public function getCheckoutFacade()
    {
        return $this->getDependencyContainer()->createCheckoutFacade();
    }
}
