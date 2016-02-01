<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ItemGrouper\Business\Model;

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
     * @param \Generated\Shared\Transfer\GroupableContainerTransfer $groupableItems
     *
     * @return \Generated\Shared\Transfer\GroupableContainerTransfer
     */
    public function groupByKey(GroupableContainerTransfer $groupableItems)
    {
        if ($this->neverGroup()) {
            return $groupableItems;
        }

        if ($this->isThresholdReached($groupableItems) === false) {
            return $groupableItems;
        }

        foreach ($groupableItems->getItems() as $item) {
            $this->fillIndex($item);
        }

        return (new GroupableContainerTransfer())->setItems(new \ArrayObject($this->groupedItems));
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     *
     * @return void
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
     * @param \Generated\Shared\Transfer\GroupableContainerTransfer $groupableItems
     *
     * @return bool
     */
    protected function isThresholdReached(GroupableContainerTransfer $groupableItems)
    {
        return ($this->threshold < count($groupableItems->getItems()));
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     *
     * @return void
     */
    protected function setQuantity($item)
    {
        if ($this->regroupAllItemCollection === true) {
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
