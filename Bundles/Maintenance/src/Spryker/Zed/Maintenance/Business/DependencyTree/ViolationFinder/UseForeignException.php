<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\ViolationFinder;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;

class UseForeignException implements ViolationFinderInterface
{

    /**
     * @param array $dependency
     *
     * @return bool
     */
    public function isViolation(array $dependency)
    {
        return (preg_match('/Exception/', $dependency[DependencyTree::META_FOREIGN_CLASS_NAME]));
    }

}
