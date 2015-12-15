<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ItemGrouper\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\GroupableContainerTransfer;

/**
 * @method ItemGrouperDependencyContainer getDependencyContainer()
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
        return $this->getDependencyContainer()->createGrouper()->groupByKey($groupAbleItems);
    }

    /**
     * @param GroupableContainerTransfer $groupableItems
     *
     * @return GroupableContainerTransfer
     */
    public function groupItemsByKeyForNewCollection(GroupableContainerTransfer $groupableItems)
    {
        return $this->getDependencyContainer()
            ->createGrouper($regroupAllItemCollection = true)
            ->groupByKey($groupableItems);
    }

}
