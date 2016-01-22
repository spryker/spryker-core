<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;

class TreeFilter
{

    /**
     * @var AbstractDependencyFilter[]
     */
    private $filter;

    public function addFilter(AbstractDependencyFilter $filter)
    {
        $this->filter[] = $filter;

        return $this;
    }

    /**
     * @param array $dependencyTree
     *
     * @return array
     */
    public function filter(array $dependencyTree)
    {
        $filteredTree = [];
        foreach ($dependencyTree as $bundle => $foreignBundles) {
            $filteredTree[$bundle] = [];
            foreach ($foreignBundles as $foreignBundle => $dependencies) {
                $filteredTree[$bundle][$foreignBundle] = [];
                foreach ($dependencies as $dependency) {
                    if (!$this->shouldBeFiltered($dependency)) {
                        $filteredTree[$bundle][$foreignBundle][] = $dependency;
                    }
                }
            }
        }

        return $filteredTree;
    }

    /**
     * @param array $dependency
     *
     * @return bool
     */
    protected function shouldBeFiltered(array $dependency)
    {
        $filterDependency = false;
        foreach ($this->filter as $filter) {
            if (!$filter->filter($dependency[DependencyTree::META_BUNDLE], $dependency)) {
                $filterDependency = true;
            }
        }

        return $filterDependency;
    }
}
