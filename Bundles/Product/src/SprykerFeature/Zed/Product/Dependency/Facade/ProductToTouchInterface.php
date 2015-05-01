<?php

namespace SprykerFeature\Zed\Product\Dependency\Facade;

interface ProductToTouchInterface
{
    /**
     * @param string $itemType
     * @param int $itemId
     *
     * @return bool
     */
    public function touchActive($itemType, $itemId);
}
