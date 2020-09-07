<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyFilter;

class TreeFilter implements TreeFilterCompositeInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\DependencyFilterInterface[]
     */
    protected $filter;

    /**
     * @param \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\DependencyFilterInterface $filter
     *
     * @return $this
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
