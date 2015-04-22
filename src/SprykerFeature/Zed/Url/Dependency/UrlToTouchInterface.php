<?php


namespace SprykerFeature\Zed\Url\Dependency;


interface UrlToTouchInterface
{
    /**
     * @param string $itemType
     * @param int $itemId
     *
     * @return bool
     */
    public function touchActive($itemType, $itemId);
}
