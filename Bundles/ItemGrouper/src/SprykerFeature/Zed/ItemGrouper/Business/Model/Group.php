<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ItemGrouper\Business\Model;

use Generated\Client\Ide\Cart;
use Generated\Shared\Cart\CartItemInterface;
use Generated\Shared\ItemGrouper;
use Generated\Shared\ItemGrouper\GroupableContainerInterface;
use Generated\Shared\Sales\OrderItemInterface;
use Generated\Shared\Transfer\GroupableContainerTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class Group
{
    /**
     * @var ItemTransfer
     */
    private $groupedItems = [];

    /**
     * @var integer
     */
    private $threshold;

    /**
     * @param integer $threshold
     */
    public function __construct($threshold)
    {
        $this->threshold = $threshold;
    }

    /**
     * @param GroupableContainerInterface $groupableItems
     *
     * @return GroupableContainerInterface
     */
    public function groupByKey(GroupableContainerInterface $groupableItems)
    {
        if ($this->isThresholdReached($groupableItems)) {
            return $groupableItems;
        }

        foreach ($groupableItems->getItems() as $item) {
            $this->fillIndex($item);
        }

        return (new GroupableContainerTransfer())->setItems(new \ArrayObject($this->groupedItems));
    }

    /**
     * @param ItemTransfer $item
     */
    protected function fillIndex($item)
    {
        $groupKey = $item->getGroupKey();
        if (!isset($this->groupedItems[$groupKey])) {
            $item->setQuantity(1);
            $this->groupedItems[$groupKey] = clone $item;
        } else {
            $groupedItem = $this->groupedItems[$groupKey];
            $groupedOrderItems[$groupKey] = $groupedItem->setQuantity(
                $groupedItem->getQuantity() + $item->getQuantity()
            );
        }
    }

    /**
     * @param GroupableContainerInterface $groupableItems
     *
     * @return bool
     */
    protected function isThresholdReached(GroupableContainerInterface $groupableItems)
    {
        return ($this->threshold > 0 && $this->threshold < count($groupableItems->getItems()));
    }
}
