<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Dependency\Facade;

class SalesToRefundBridge implements SalesToRefundInterface
{

    /**
     * @var \Spryker\Zed\Refund\Business\RefundFacade
     */
    protected $refundFacade;

    /**
     * @param \Spryker\Zed\Refund\Business\RefundFacade $refundFacade
     */
    public function __construct($refundFacade)
    {
        $this->refundFacade = $refundFacade;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\RefundTransfer[]
     */
    public function getRefundsByIdSalesOrder($idSalesOrder)
    {
        return $this->refundFacade->getRefundsByIdSalesOrder($idSalesOrder);
    }

}
