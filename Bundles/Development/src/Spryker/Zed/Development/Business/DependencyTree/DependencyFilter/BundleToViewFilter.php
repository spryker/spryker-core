<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyFilter;

use Spryker\Zed\Development\Business\DependencyTree\DependencyTree;

class BundleToViewFilter implements DependencyFilterInterface
{
    /**
     * @var string
     */
    protected $bundle;

    /**
     * @param string $bundle
     */
    public function __construct($bundle)
    {
        $this->bundle = $bundle;
    }

    /**
     * @param array $dependency
     *
     * @return bool
     */
    public function filter(array $dependency)
    {
        return ($dependency[DependencyTree::META_MODULE] !== $this->bundle);
    }
}
