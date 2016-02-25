<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Zed\Sales\Business\Model;

interface OrderCommentReaderInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderDetailsCommentsTransfer
     */
    public function getCommentsByIdSalesOrder($idSalesOrder);
}
