<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;

class InternalDependencyFilter implements DependencyFilterInterface
{

    /**
     * @param array $dependency
     *
     * @return bool
     */
    public function filter(array $dependency)
    {
        return !isset($dependency[DependencyTree::META_FOREIGN_IS_EXTERNAL]);
    }

}
