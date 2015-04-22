<?php

namespace SprykerFeature\Zed\ProductSearch\Dependency\Facade;

interface ProductSearchToTouchInterface
{
    /**
     * @param string $itemType
     * @param int $itemId
     *
     * @return bool
     */
    public function touchActive($itemType, $itemId);
}
