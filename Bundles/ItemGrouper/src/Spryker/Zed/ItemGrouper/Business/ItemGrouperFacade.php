<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ItemGrouper\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\GroupableContainerTransfer;

/**
 * @method ItemGrouperBusinessFactory getFactory()
 */
class ItemGrouperFacade extends AbstractFacade
{

    /**
     * @param GroupableContainerTransfer $groupAbleItems
     *
     * @return GroupableContainerTransfer
     */
    public function groupItemsByKey(GroupableContainerTransfer $groupAbleItems)
    {
        return $this->getFactory()->createGrouper()->groupByKey($groupAbleItems);
    }

    /**
     * @param GroupableContainerTransfer $groupableItems
     *
     * @return GroupableContainerTransfer
     */
    public function groupItemsByKeyForNewCollection(GroupableContainerTransfer $groupableItems)
    {
        return $this->getFactory()
            ->createGrouper($regroupAllItemCollection = true)
            ->groupByKey($groupableItems);
    }

}
