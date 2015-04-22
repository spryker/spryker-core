<?php

namespace SprykerFeature\Shared\Cart2\Transfer;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransferCollection;

class ItemCollection extends AbstractTransferCollection implements ItemCollectionInterface
{
    /**
     * @var ItemInterface
     */
    protected $transferObjectClass = 'Cart2\\Item';
}
