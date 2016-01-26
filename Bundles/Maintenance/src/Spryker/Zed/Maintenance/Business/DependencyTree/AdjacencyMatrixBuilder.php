<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\TreeFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeReader\DependencyTreeReaderInterface;

class AdjacencyMatrixBuilder
{

    const FROM_LAYER_TO_LAYER = 'fromLayerToLayer';

    /**
     * @var DependencyTreeReaderInterface
     */
    protected $dependencyTreeReader;

    /**
     * @var TreeFilter
     */
    protected $filter;

    /**
     * @var array
     */
    private $matrix = [];

    /**
     * @param DependencyTreeReaderInterface $dependencyTreeReader
     * @param TreeFilter $filter
     */
    public function __construct(
        DependencyTreeReaderInterface $dependencyTreeReader,
        TreeFilter $filter
    ) {
        $this->dependencyTreeReader = $dependencyTreeReader;
        $this->filter = $filter;
    }

    /**
     * @return bool
     */
    public function build()
    {
        $dependencyTree = $this->filter->filter($this->dependencyTreeReader->read());
        $this->buildMatrixStructure($dependencyTree);

        foreach ($dependencyTree as $bundle => $foreignBundles) {
            $this->addBundles($bundle, $foreignBundles);
        }

        return $this->matrix;
    }

    /**
     * @param array $dependencyTree
     *
     * @return void
     */
    protected function buildMatrixStructure(array $dependencyTree)
    {
        foreach ($dependencyTree as $rowBundle => $rowForeignBundles) {
            $this->matrix[$rowBundle] = [];
            $this->addColumnsToMatrix($dependencyTree, $rowBundle);
        }
    }

    /**
     * @param array $dependencyTree
     * @param string $rowBundle
     *
     * @return void
     */
    protected function addColumnsToMatrix(array $dependencyTree, $rowBundle)
    {
        foreach ($dependencyTree as $columnBundle => $columnForeignBundles) {
            $this->matrix[$rowBundle][$columnBundle] = [self::FROM_LAYER_TO_LAYER => []];
        }
    }

    /**
     * @param string $bundle
     * @param array $foreignBundles
     *
     * @return void
     */
    protected function addBundles($bundle, array $foreignBundles)
    {
        foreach ($foreignBundles as $foreignBundle => $dependencies) {
            $this->addForeignBundles($bundle, $foreignBundle, $dependencies);
        }
    }

    /**
     * @param string $bundle
     * @param string $foreignBundle
     * @param array $dependencies
     *
     * @return void
     */
    protected function addForeignBundles($bundle, $foreignBundle, array $dependencies)
    {
        foreach ($dependencies as $dependency) {
            if (!array_key_exists($foreignBundle, $this->matrix[$bundle])) {
                $this->matrix[$bundle][$foreignBundle] = [self::FROM_LAYER_TO_LAYER => []];
            }
            $name = $dependency[DependencyTree::META_LAYER] . ' => ' . $dependency[DependencyTree::META_FOREIGN_LAYER];

            $info = $this->matrix[$bundle][$foreignBundle];
            $info[self::FROM_LAYER_TO_LAYER][$name] = $name;

            $this->matrix[$bundle][$foreignBundle] = $info;
        }
    }

}
