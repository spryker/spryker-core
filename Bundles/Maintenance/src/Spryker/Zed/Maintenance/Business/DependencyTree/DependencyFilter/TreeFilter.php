<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter;

class TreeFilter implements TreeFilterCompositeInterface
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
        foreach ($dependencyTree as $dependency) {
            if (!$this->shouldBeFiltered($dependency)) {
                $filteredTree[] = $dependency;
            }
        }

        return $filteredTree;
    }

    /**
     * @param array $dependency
     *
     * @return bool
     */
    private function shouldBeFiltered(array $dependency)
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
