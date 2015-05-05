<?php

namespace SprykerFeature\Shared\Cart\Transfer;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransferCollection;

class ItemCollection extends AbstractTransferCollection implements ItemCollectionInterface
{
    /**
     * @var ItemInterface
     */
    protected $transferObjectClass = 'Cart\\Item';
}
