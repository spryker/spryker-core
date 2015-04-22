<?php

namespace SprykerFeature\Zed\Application\Business\Model\Navigation\Collector;

interface NavigationCollectorInterface
{
    /**
     * @param array $navigationFiles
     * @throws \ErrorException
     */
    public function mergeNavigationFiles(array $navigationFiles);
}