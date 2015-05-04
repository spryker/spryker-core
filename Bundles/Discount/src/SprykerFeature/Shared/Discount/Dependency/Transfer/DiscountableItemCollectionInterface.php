<?php

namespace SprykerFeature\Shared\Discount\Dependency\Transfer;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableItemCollectionInterface;
use SprykerFeature\Shared\Library\TransferObject\TransferCollectionInterface;

interface DiscountableItemCollectionInterface extends TransferCollectionInterface, CalculableItemCollectionInterface
{
}
