<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyFilter;

use Spryker\Zed\Development\Business\DependencyTree\DependencyTree;

/**
 * Filters dependencies from DependencyTree where dependency was found in a test class.
 */
class InTestDependencyFilter implements DependencyFilterInterface
{
    /**
     * @param array $dependency
     *
     * @return bool
     */
    public function filter(array $dependency)
    {
        return $dependency[DependencyTree::META_IN_TEST];
    }
}
