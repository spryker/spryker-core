<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart\Dependency\Facade;

use Generated\Shared\Transfer\GroupableContainerTransfer;
use Spryker\Zed\ItemGrouper\Business\ItemGrouperFacade;

class CartToItemGrouperBridge implements CartToItemGrouperInterface
{

    /**
     * @var ItemGrouperFacade
     */
    protected $itemGrouperFacade;

    /**
     * @param ItemGrouperFacade $itemGrouperFacade
     */
    public function __construct($itemGrouperFacade)
    {
        $this->itemGrouperFacade = $itemGrouperFacade;
    }

    /**
     * @param GroupableContainerTransfer $groupAbleItems
     *
     * @return GroupableContainerTransfer
     */
    public function groupItemsByKey(GroupableContainerTransfer $groupAbleItems)
    {
        return $this->itemGrouperFacade->groupItemsByKey($groupAbleItems);
    }

}
