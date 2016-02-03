<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;

class ConstantsToForeignConstantsFilter implements DependencyFilterInterface
{

    /**
     * @param array $dependency
     *
     * @return bool
     */
    public function filter(array $dependency)
    {
        $constantsClassNamePattern = '/(.*?)Constants/';
        $isClassNameConstants = preg_match($constantsClassNamePattern, $dependency[DependencyTree::META_CLASS_NAME]);
        $isForeignClassNameConstants = preg_match($constantsClassNamePattern, $dependency[DependencyTree::META_CLASS_NAME]);

        return ($isClassNameConstants && $isForeignClassNameConstants);
    }

}
