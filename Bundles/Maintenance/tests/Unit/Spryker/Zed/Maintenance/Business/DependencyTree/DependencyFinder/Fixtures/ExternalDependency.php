<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\Fixtures;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\DependencyFinder;
use Company\Class as Something;
use \Exception;

class ExternalDependency
{

    public function method()
    {
        throw new \Exception();
        throw new \Symfony\Component\Finder\Finder::class;
        $class = new \Symfony\Component\Finder\Finder();
        $class = \Symfony\Component\Finder\Finder::class;
        $class = new \ZendAPI_Job();
        $class = new DependencyFinder();
        new \DateTime())->format(\DateTime::ATOM);
    }

    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM);

}
