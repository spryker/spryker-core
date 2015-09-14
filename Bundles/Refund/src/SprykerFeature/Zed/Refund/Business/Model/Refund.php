<?php

namespace SprykerFeature\Zed\Refund\Business\Model;

use Generated\Shared\Transfer\RefundTransfer;
use SprykerFeature\Zed\Refund\Persistence\Propel\SpyRefund;

class Refund
{

    /**
     * @param RefundTransfer $refundTransfer
     *
     * @return RefundTransfer
     */
    public function saveRefund(RefundTransfer $refundTransfer)
    {
        $refundEntity = new SpyRefund();
        $refundEntity->fromArray($refundTransfer->toArray());
        $refundEntity->save();

        return $refundTransfer;
    }

}
