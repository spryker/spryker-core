<?php

namespace SprykerFeature\Shared\Cart2\Transfer;

use Generated\Shared\Transfer\Cart2ItemTransfer;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableItemCollectionInterface;

interface ItemCollectionInterface extends DiscountableItemCollectionInterface
{

    /**
     * @return Cart2ItemTransfer[]
     */
    public function getCartItems();

}
