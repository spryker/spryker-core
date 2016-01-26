<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;

class DependencyFilter implements DependencyFilterInterface
{

    /**
     * @var DependencyFilterInterface[]
     */
    private $dependencyFilter = [];

    /**
     * @param DependencyFilterInterface $dependencyFilter
     *
     * @return $this
     */
    public function addFilter(DependencyFilterInterface $dependencyFilter)
    {
        $this->dependencyFilter[] = $dependencyFilter;

        return $this;
    }

    /**
     * @param array $dependency
     *
     * @return bool
     */
    public function filter(array $dependency)
    {
        $filterDependency = false;
        foreach ($this->dependencyFilter as $dependencyFilter) {
            if ($dependencyFilter->filter($dependency)) {
                $filterDependency = true;
            }
        }

        return $filterDependency;
    }
}
