<?php

namespace SprykerFeature\Zed\SearchPage\Dependency\Facade;

interface SearchPageToTouchInterface
{

    /**
     * @param string $itemType
     * @param int $itemId
     *
     * @return bool
     */
    public function touchActive($itemType, $itemId);
}