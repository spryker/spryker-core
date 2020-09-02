<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyFilter;

use Spryker\Zed\Development\Business\DependencyTree\DependencyTree;

class ClassNameFilter implements DependencyFilterInterface
{
    /**
     * @var string
     */
    protected $pattern;

    /**
     * @param string $pattern
     */
    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @param array $dependency
     *
     * @return bool
     */
    public function filter(array $dependency)
    {
        $foreignClassName = $dependency[DependencyTree::META_FOREIGN_CLASS_NAME];

        if (preg_match($this->pattern, $foreignClassName)) {
            return true;
        }

        return false;
    }
}
