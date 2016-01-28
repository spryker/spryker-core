<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\TreeFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeReader\DependencyTreeReaderInterface;

class AdjacencyMatrixBuilder
{

    /**
     * @var DependencyTreeReaderInterface
     */
    private $dependencyTreeReader;

    /**
     * @var TreeFilter
     */
    private $filter;

    /**
     * @var array
     */
    private $bundleList;

    /**
     * @var array
     */
    private $matrix = [];

    /**
     * @param array $bundleList
     * @param DependencyTreeReaderInterface $dependencyTreeReader
     * @param TreeFilter $filter
     */
    public function __construct(
        array $bundleList,
        DependencyTreeReaderInterface $dependencyTreeReader,
        TreeFilter $filter
    ) {
        $this->bundleList = $bundleList;
        $this->dependencyTreeReader = $dependencyTreeReader;
        $this->filter = $filter;
    }

    /**
     * @return bool
     */
    public function build()
    {
        $dependencyTree = $this->filter->filter($this->dependencyTreeReader->read());
        $this->buildMatrixStructure();

        foreach ($dependencyTree as $dependency) {

            $bundle = $dependency[DependencyTree::META_BUNDLE];
            $foreignBundle = $dependency[DependencyTree::META_FOREIGN_BUNDLE];
            $info = $this->matrix[$bundle][$foreignBundle];

            $info[] = $dependency[DependencyTree::META_CLASS_NAME] . ' => ' . $dependency[DependencyTree::META_FOREIGN_CLASS_NAME];

            $this->matrix[$bundle][$foreignBundle] = $info;
        }

        return $this->matrix;
    }

    /**
     * @return void
     */
    private function buildMatrixStructure()
    {
        foreach ($this->bundleList as $rowBundle) {
            $this->matrix[$rowBundle] = [];
            foreach ($this->bundleList as $columnBundle) {
                $this->matrix[$rowBundle][$columnBundle] = [];
            }
        }
    }

}
