<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\ViolationFinder;

use Spryker\Zed\Development\Business\DependencyTree\DependencyTree;

class UseForeignException implements ViolationFinderInterface
{
    /**
     * @param array $dependency
     *
     * @return bool
     */
    public function isViolation(array $dependency)
    {
        return (bool)preg_match('/Exception/', $dependency[DependencyTree::META_FOREIGN_CLASS_NAME]);
    }
}
