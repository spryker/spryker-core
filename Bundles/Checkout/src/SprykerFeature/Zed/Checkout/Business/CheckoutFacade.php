<?php
namespace SprykerFeature\Zed\Checkout\Business;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Shared\ZedRequest\Client\RequestInterface;

class CheckoutFacade extends AbstractFacade
{

    /**
     * @param Order   $orderTransfer
     * @param RequestInterface $transferRequest
     * @param array   $logContext
     * @return \SprykerEngine\Zed\Kernel\Business\ModelResult
     */
    public function saveOrder(Order $orderTransfer, RequestInterface $transferRequest, array $logContext)
    {
        return $this->factory
            ->createModelWorkflowDefinitionSaveOrder($orderTransfer, $transferRequest, $this->factory)
            ->run($logContext);
    }
}
