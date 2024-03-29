<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyFilter;

use Spryker\Zed\Development\Business\DependencyTree\DependencyTree;

class InvalidForeignBundleFilter implements DependencyFilterInterface
{
    /**
     * @var array<string>
     */
    protected $allowedBundles;

    /**
     * @param array<string> $allowedBundles
     */
    public function __construct(array $allowedBundles)
    {
        $this->allowedBundles = $allowedBundles;
    }

    /**
     * @param array<string, string> $dependency
     *
     * @return bool
     */
    public function filter(array $dependency)
    {
        return (!in_array($dependency[DependencyTree::META_FOREIGN_BUNDLE], $this->allowedBundles, true) && !isset($dependency[DependencyTree::META_FOREIGN_IS_EXTERNAL]));
    }
}
