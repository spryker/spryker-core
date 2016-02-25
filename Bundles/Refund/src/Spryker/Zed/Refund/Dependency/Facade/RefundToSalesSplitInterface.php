<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Zed\Refund\Dependency\Facade;

interface RefundToSalesSplitInterface
{
    /**
     * @param int $idSalesOrderItem
     * @param int $quantity
     *
     *
     * @return \Generated\Shared\Transfer\ItemSplitResponseTransfer
     */
    public function splitSalesOrderItem($idSalesOrderItem, $quantity);
}
