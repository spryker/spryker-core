<?php

namespace SprykerFeature\Zed\Application\Business\Model\Navigation\Collector;

use SprykerFeature\Zed\Application\Business\Model\Navigation\SchemaFinder\NavigationSchemaFinderInterface;

interface NavigationCollectorInterface
{

    /**
     * @param NavigationSchemaFinderInterface $navigationSchemaFinder
     *
     * @return array
     */
    public function mergeNavigationFiles(NavigationSchemaFinderInterface $navigationSchemaFinder);

}
