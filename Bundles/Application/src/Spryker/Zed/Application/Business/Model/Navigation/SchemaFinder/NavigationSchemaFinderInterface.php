<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Business\Model\Navigation\SchemaFinder;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

interface NavigationSchemaFinderInterface
{

    /**
     * @return \Symfony\Component\Finder\Finder|SplFileInfo[]
     */
    public function getSchemaFiles();

}
