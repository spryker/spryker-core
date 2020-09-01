<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyFilter;

class DependencyFilter implements DependencyFilterCompositeInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\DependencyFilterInterface[]
     */
    protected $dependencyFilter = [];

    /**
     * @param \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\DependencyFilterInterface $dependencyFilter
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
