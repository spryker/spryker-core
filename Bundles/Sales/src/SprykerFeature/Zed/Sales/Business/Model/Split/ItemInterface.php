<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Sales\Business\Model\Split;

use Generated\Shared\Transfer\ItemSplitResponseTransfer;

interface ItemInterface
{

    /**
     * @param int $idSalesOrderItem
     * @param int $quantityToSplit
     *
     * @throws \Exception
     *
     * @return ItemSplitResponseTransfer
     */
    public function split($idSalesOrderItem, $quantityToSplit);

}
