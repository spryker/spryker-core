<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\ViolationChecker;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\DependencyFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeReader\DependencyTreeReaderInterface;
use Spryker\Zed\Maintenance\Business\DependencyTree\ViolationFinder\ViolationFinderInterface;

class DependencyViolationChecker implements DependencyViolationCheckerInterface
{

    /**
     * @var DependencyTreeReaderInterface
     */
    private $treeReader;

    /**
     * @var ViolationFinderInterface
     */
    private $violationFinder;

    /**
     * @var DependencyFilter
     */
    private $dependencyFilter;

    /**
     * @var array
     */
    private $dependencyViolations = [];

    /**
     * @param \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeReader\DependencyTreeReaderInterface $treeReader
     * @param \Spryker\Zed\Maintenance\Business\DependencyTree\ViolationFinder\ViolationFinderInterface $violationFinder
     * @param \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\DependencyFilter $dependencyFilter
     */
    public function __construct(DependencyTreeReaderInterface $treeReader, ViolationFinderInterface $violationFinder, DependencyFilter $dependencyFilter)
    {
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
