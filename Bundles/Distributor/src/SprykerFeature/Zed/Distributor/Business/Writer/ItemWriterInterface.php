<?php

namespace SprykerFeature\Zed\Distributor\Business\Writer;

interface ItemWriterInterface
{
    /**
     * @param string $itemType
     * @param int $idItem
     *
     * @return int
     */
    public function touchItem($itemType, $idItem);

    /**
     * @param $itemType
     *
     * @return bool
     */
    public function touchAllItemsByType($itemType);
}
