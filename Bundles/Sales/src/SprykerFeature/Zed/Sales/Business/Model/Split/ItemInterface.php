<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Sales\Business\Model\Split;

use Generated\Shared\Sales\ItemSplitResponseInterface;

interface ItemInterface
{

    /**
     * @param int $idSalesOrderItem
     * @param int $quantityToSplit
     *
     * @throws \Exception
     *
     * @return ItemSplitResponseInterface
     */
    public function split($idSalesOrderItem, $quantityToSplit);

}
