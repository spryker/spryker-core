<?php

namespace SprykerFeature\Shared\Discount\Dependency\Transfer;

use Generated\Shared\Transfer\Calculation\DependencyCalculableItemCollectionInterfaceTransfer;
use SprykerFeature\Shared\Library\TransferObject\TransferCollectionInterface;

interface DiscountableItemCollectionInterface extends TransferCollectionInterface, CalculableItemCollectionInterface
{
}
