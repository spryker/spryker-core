<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;

class EngineBundleFilter implements DependencyFilterInterface
{

    /**
     * @var array
     */
    private $filterBundles = [];

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
        return in_array($dependency[DependencyTree::META_BUNDLE], $this->filterBundles);
    }

}
