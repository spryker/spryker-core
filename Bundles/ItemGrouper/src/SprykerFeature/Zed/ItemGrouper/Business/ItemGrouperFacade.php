<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ItemGrouper\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\ItemGrouper;
use Generated\Shared\ItemGrouper\GroupableContainerInterface;

/**
 * @method ItemGrouperDependencyContainer getDependencyContainer()
 */
class ItemGrouperFacade extends AbstractFacade
{
    /**
     * @param GroupableContainerInterface $groupAbleItems
     *
     * @return GroupableContainerInterface
     */
    public function groupItemsByKey(GroupableContainerInterface $groupAbleItems)
    {
        return $this->getDependencyContainer()->createGrouper()->groupByKey($groupAbleItems);
    }

    /**
     * @param GroupableContainerInterface $groupableItems
     *
     * @return GroupableContainerInterface
     */
    public function groupItemsByKeyForNewCollection(GroupableContainerInterface $groupableItems)
    {
        return $this->getDependencyContainer()
            ->createGrouper($regroupAllItemCollection = true)
            ->groupByKey($groupableItems);
    }
}
