<?php

namespace SprykerFeature\Zed\Refund\Business\Model;

use Generated\Shared\Transfer\RefundCommentTransfer;
use SprykerFeature\Zed\Refund\Persistence\Propel\SpyRefund;

class RefundComment
{

    /**
     * @param RefundCommentTransfer $refundCommentTransfer
     *
     * @return RefundCommentTransfer
     */
    public function saveRefundComment(RefundCommentTransfer $refundCommentTransfer)
    {
        $refundCommentEntity = new SpyRefund();
        $refundCommentEntity->fromArray($refundCommentTransfer->toArray());
        $refundCommentEntity->save();

        return $refundCommentTransfer;
    }

}
