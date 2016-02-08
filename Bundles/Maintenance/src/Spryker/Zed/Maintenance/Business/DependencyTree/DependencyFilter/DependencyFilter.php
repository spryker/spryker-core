<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter;

class DependencyFilter implements DependencyFilterCompositeInterface
{

    /**
     * @var \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\DependencyFilterInterface[]
     */
    private $dependencyFilter = [];

    /**
     * @param \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\DependencyFilterInterface $dependencyFilter
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
