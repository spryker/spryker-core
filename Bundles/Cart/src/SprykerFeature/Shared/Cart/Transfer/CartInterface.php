<?php

namespace SprykerFeature\Shared\Cart\Transfer;

use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;

interface CartInterface extends DiscountableContainerInterface
{
    /**
     * @return \ArrayObject|ItemCollectionInterface
     */
    public function getItems();

    /**
     * @param \ArrayObject $items
     *
     * @return $this
     */
    public function setItems(\ArrayObject $items);
}
