<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ItemGrouper\Business\Model;

use Generated\Shared\ItemGrouper\GroupableContainerInterface;
use Generated\Shared\Transfer\GroupableContainerTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class Group
{

    /**
     * @var ItemTransfer
     */
    private $groupedItems = [];

    /**
     * @var int
     */
    private $threshold;

    /**
     * @var bool
     */
    private $regroupAllItemCollection;

    /**
     * @param int $threshold
     * @param bool $regroupAllItemCollection;
     */
    public function __construct($threshold, $regroupAllItemCollection)
    {
        $this->threshold = $threshold;
        $this->regroupAllItemCollection = $regroupAllItemCollection;
    }

    /**
     * @param GroupableContainerInterface $groupableItems
     *
     * @return GroupableContainerInterface
     */
    public function groupByKey(GroupableContainerInterface $groupableItems)
    {
        if ($this->neverGroup()) {
            return $groupableItems;
        }

        if (false === $this->isThresholdReached($groupableItems)) {
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
        $groupKey = !empty($item->getGroupKey()) ? $item->getGroupKey() : count($this->groupedItems) + 1;

        if (!isset($this->groupedItems[$groupKey])) {
            $this->setQuantity($item);
            $this->groupedItems[$groupKey] = $item;
        } else {
            $groupedItem = $this->groupedItems[$groupKey];
            $this->groupedItems[$groupKey] = $groupedItem->setQuantity(
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
        return ($this->threshold < count($groupableItems->getItems()));
    }

    /**
     * @param ItemTransfer $item
     */
    protected function setQuantity($item)
    {
        if (true === $this->regroupAllItemCollection) {
            $item->setQuantity(1);
        }
    }

    /**
     * @return bool
     */
    protected function neverGroup()
    {
        return $this->threshold < 0;
    }

}
