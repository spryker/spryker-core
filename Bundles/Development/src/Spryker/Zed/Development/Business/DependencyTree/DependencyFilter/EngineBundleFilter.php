<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyFilter;

use Spryker\Zed\Development\Business\DependencyTree\DependencyTree;

class EngineBundleFilter implements DependencyFilterInterface
{
    /**
     * @var array
     */
    protected $filterBundles = [];

    /**
     * @param string $pathToBundleConfig
     */
    public function __construct($pathToBundleConfig)
    {
        $bundleList = json_decode(file_get_contents($pathToBundleConfig), true);
        $this->filterBundles = array_keys($bundleList);
    }

    /**
     * @param array $dependency
     *
     * @return bool
     */
    public function filter(array $dependency)
    {
        return in_array($dependency[DependencyTree::META_MODULE], $this->filterBundles);
    }
}
