<?php

namespace SprykerFeature\Zed\Stock\Dependency\Facade;


interface StockToTouchInterface
{

    /**
     * @param string $itemType
     * @param bool $itemId
     * @return bool
     */
    public function touchActive($itemType, $itemId);
}
