<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Business\Model\Navigation\SchemaFinder;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

interface NavigationSchemaFinderInterface
{

    /**
     * @return Finder|SplFileInfo[]
     */
    public function getSchemaFiles();

}
