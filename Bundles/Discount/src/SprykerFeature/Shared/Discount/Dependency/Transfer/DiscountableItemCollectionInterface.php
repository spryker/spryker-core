<?php

namespace SprykerFeature\Shared\Discount\Dependency\Transfer;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableItemCollectionInterface;
use SprykerEngine\Shared\Transfer\TransferCollectionInterface;

interface DiscountableItemCollectionInterface extends TransferCollectionInterface, CalculableItemCollectionInterface
{
}
