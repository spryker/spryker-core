<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter;

class TreeFilter
{

    /**
     * @var DependencyFilterInterface[]
     */
    private $filter;

    /**
     * @param DependencyFilterInterface $filter
     *
     * @return self
     */
    public function addFilter(DependencyFilterInterface $filter)
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
            foreach ($foreignBundles as $foreignBundle => $dependencies) {
                foreach ($dependencies as $dependency) {
                    if (!$this->shouldBeFiltered($dependency)) {
                        if (!array_key_exists($bundle, $filteredTree)) {
                            $filteredTree[$bundle] = [];
                        }
                        if (!array_key_exists($foreignBundle, $filteredTree[$bundle])) {
                            $filteredTree[$bundle][$foreignBundle] = [];
                        }
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
            if ($filter->filter($dependency)) {
                $filterDependency = true;
            }
        }

        return $filterDependency;
    }

}
