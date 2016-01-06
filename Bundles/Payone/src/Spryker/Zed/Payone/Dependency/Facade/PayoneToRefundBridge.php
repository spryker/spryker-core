<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Dependency\Facade;

use Spryker\Zed\Refund\Business\RefundFacade;
use Generated\Shared\Transfer\OrderTransfer;

class PayoneToRefundBridge implements PayoneToRefundInterface
{

    /**
     * @var RefundFacade
     */
    protected $refundFacade;

    /**
     * @param RefundFacade $refundFacade
     */
    public function __construct($refundFacade)
    {
        $this->refundFacade = $refundFacade;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return int
     */
    public function calculateRefundableAmount(OrderTransfer $orderTransfer)
    {
        return $this->refundFacade->calculateRefundableAmount($orderTransfer);
    }

}
