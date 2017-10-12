<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyFilter;

use Spryker\Zed\Development\Business\DependencyTree\DependencyTree;

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
