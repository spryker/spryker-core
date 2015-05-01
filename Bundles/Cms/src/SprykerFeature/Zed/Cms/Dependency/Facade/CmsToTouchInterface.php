<?php

namespace SprykerFeature\Zed\Cms\Dependency\Facade;

interface CmsToTouchInterface
{
    /**
     * @param string $itemType
     * @param int $itemId
     *
     * @return bool
     */
    public function touchActive($itemType, $itemId);
}
