<?php

namespace SprykerFeature\Shared\Cart\Transfer;

use Generated\Shared\Transfer\Calculation\DependencyCalculableItemCollectionInterfaceTransfer;
use Generated\Shared\Transfer\Discount\DependencyDiscountableContainerInterfaceTransfer;

interface CartInterface extends DiscountableContainerInterface
{
    /**
     * @return CalculableItemCollectionInterface|ItemCollectionInterface
     */
    public function getItems();

    /**
     * @param CalculableItemCollectionInterface $items
     *
     * @return $this
     */
    public function setItems(CalculableItemCollectionInterface $items);
}
