<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ItemGrouperWishlistConnector\Dependency\Facade;

use Spryker\Zed\ItemGrouper\Business\ItemGrouperFacade;
use Generated\Shared\Transfer\GroupableContainerTransfer;

class ItemGrouperWishlistConnectorToItemGrouperBridge implements ItemGrouperWishlistConnectorToItemGrouperInterface
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
