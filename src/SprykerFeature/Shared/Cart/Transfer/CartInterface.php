<?php

namespace SprykerFeature\Shared\Cart\Transfer;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableItemCollectionInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;

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