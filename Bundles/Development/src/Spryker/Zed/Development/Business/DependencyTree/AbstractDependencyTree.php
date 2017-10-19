<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree;

use Symfony\Component\Finder\SplFileInfo;

abstract class AbstractDependencyTree
{
    /**
     * @var array
     */
    protected $dependencyTree = [];

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     * @param string $to
     * @param array $dependency
     *
     * @return void
     */
    abstract public function addDependency(SplFileInfo $fileInfo, $to, array $dependency = []);

    /**
     * @return array
     */
    public function getDependencyTree()
    {
        return $this->dependencyTree;
    }
}
