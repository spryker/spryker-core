<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree;

use Symfony\Component\Finder\SplFileInfo;

abstract class AbstractDependencyTree
{

    /**
     * @var array
     */
    protected $dependencyTree = [];

    /**
     * @param SplFileInfo $fileInfo
     * @param string $to
     * @param array $meta
     *
     * @return void
     */
    abstract public function addDependency(SplFileInfo $fileInfo, $to, array $meta = []);

    /**
     * @return array
     */
    public function getDependencyTree()
    {
        return $this->dependencyTree;
    }

}
