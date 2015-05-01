<?php

namespace SprykerFeature\Zed\Category\Dependency\Facade;

interface CategoryToTouchInterface
{
    /**
     * @param string $itemType
     * @param int $itemId
     *
     * @return bool
     */
    public function touchActive($itemType, $itemId);
}
