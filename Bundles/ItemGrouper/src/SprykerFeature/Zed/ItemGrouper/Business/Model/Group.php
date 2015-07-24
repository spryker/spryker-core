<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ItemGrouper\Business\Model;

use Generated\Shared\ItemGrouper;
use Generated\Shared\ItemGrouper\GroupableContainerInterface;
use Generated\Shared\Transfer\GroupableContainerTransfer;

class Group
{
    /**
     * @var array
     */
    private $groupedItems = [];

    /**
     * @param GroupableContainerInterface $groupableItems
     *
     * @return GroupableContainerInterface
     */
    public function groupByKey(GroupableContainerInterface $groupableItems)
    {
        foreach ($groupableItems->getItems() as $item) {
            $this->fillIndex($item);
        }

        return (new GroupableContainerTransfer())->setItems(new \ArrayObject($this->groupedItems));
    }

    /**
     * @param $item
     */
    protected function fillIndex($item)
    {
        $groupKey = $item->getGroupKey();
        if (!isset($this->groupedItems[$groupKey])) {
            $item->setQuantity(1);
            $this->groupedItems[$groupKey] = clone $item;
        } else {
            $groupedOrderItems[$groupKey] = $this->groupedItems[$groupKey]->setQuantity(
                $this->groupedItems[$groupKey]->getQuantity() + $item->getQuantity()
            );
        }
    }
}
