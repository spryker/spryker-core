<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\ViolationChecker;

use Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\DependencyFilterInterface;
use Spryker\Zed\Development\Business\DependencyTree\DependencyTree;
use Spryker\Zed\Development\Business\DependencyTree\DependencyTreeReader\DependencyTreeReaderInterface;
use Spryker\Zed\Development\Business\DependencyTree\ViolationFinder\ViolationFinderInterface;

/**
 * @deprecated This is not used anymore.
 */
class DependencyViolationChecker implements DependencyViolationCheckerInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\DependencyTreeReader\DependencyTreeReaderInterface
     */
    protected $treeReader;

    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\ViolationFinder\ViolationFinderInterface
     */
    protected $violationFinder;

    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\DependencyFilterInterface
     */
    protected $dependencyFilter;

    /**
     * @var array
     */
    protected $dependencyViolations = [];

    /**
     * @param \Spryker\Zed\Development\Business\DependencyTree\DependencyTreeReader\DependencyTreeReaderInterface $treeReader
     * @param \Spryker\Zed\Development\Business\DependencyTree\ViolationFinder\ViolationFinderInterface $violationFinder
     * @param \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\DependencyFilterInterface $dependencyFilter
     */
    public function __construct(
        DependencyTreeReaderInterface $treeReader,
        ViolationFinderInterface $violationFinder,
        DependencyFilterInterface $dependencyFilter
    ) {
        $this->treeReader = $treeReader;
        $this->violationFinder = $violationFinder;
        $this->dependencyFilter = $dependencyFilter;
    }

    /**
     * @return array
     */
    public function getDependencyViolations()
    {
        $dependencyTree = $this->treeReader->read();
        foreach ($dependencyTree as $dependency) {
            if ($this->violationFinder->isViolation($dependency) && !$this->dependencyFilter->filter($dependency)) {
                $this->addViolation($dependency);
            }
        }

        return $this->dependencyViolations;
    }

    /**
     * @param array $dependency
     *
     * @return void
     */
    private function addViolation(array $dependency)
    {
        $this->dependencyViolations[] = $dependency[DependencyTree::META_CLASS_NAME] . ' => ' . $dependency[DependencyTree::META_FOREIGN_CLASS_NAME];
    }
}
