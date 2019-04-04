<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree;

use Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\TreeFilterInterface;
use Spryker\Zed\Development\Business\DependencyTree\DependencyTreeReader\DependencyTreeReaderInterface;

class AdjacencyMatrixBuilder implements AdjacencyMatrixBuilderInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\DependencyTreeReader\DependencyTreeReaderInterface
     */
    private $dependencyTreeReader;

    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\TreeFilterInterface
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
     * @param \Spryker\Zed\Development\Business\DependencyTree\DependencyTreeReader\DependencyTreeReaderInterface $dependencyTreeReader
     * @param \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\TreeFilterInterface $filter
     */
    public function __construct(
        array $bundleList,
        DependencyTreeReaderInterface $dependencyTreeReader,
        TreeFilterInterface $filter
    ) {
        $this->bundleList = $bundleList;
        $this->dependencyTreeReader = $dependencyTreeReader;
        $this->filter = $filter;
    }

    /**
     * @return array
     */
    public function build()
    {
        $dependencyTree = $this->filter->filter($this->dependencyTreeReader->read());
        $this->buildMatrixStructure();

        foreach ($dependencyTree as $dependency) {
            $bundle = $dependency[DependencyTree::META_MODULE];
            $foreignBundle = $dependency[DependencyTree::META_FOREIGN_BUNDLE];
            if ($bundle === 'external' || $foreignBundle === 'external' || $bundle === 'Business') {
                continue;
            }
            $info = $this->matrix[$bundle][$foreignBundle] ?? [];

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
