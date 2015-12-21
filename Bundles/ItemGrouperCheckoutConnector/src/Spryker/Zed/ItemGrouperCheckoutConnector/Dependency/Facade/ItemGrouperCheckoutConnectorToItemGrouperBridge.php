<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ItemGrouperCheckoutConnector\Dependency\Facade;

use Generated\Shared\Transfer\GroupableContainerTransfer;

class ItemGrouperCheckoutConnectorToItemGrouperBridge implements ItemGrouperCheckoutConnectorToItemGrouperInterface
{

    /**
     * @var \Spryker\Zed\ItemGrouper\Business\ItemGrouperFacade
     */
    protected $itemGrouperFacade;

    /**
     * @param \Spryker\Zed\ItemGrouper\Business\ItemGrouperFacade $itemGrouperFacade
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
