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

    public function __construct()
    {
        $bundleList = json_decode(file_get_contents(APPLICATION_VENDOR_DIR . '/spryker/spryker/bundle_config.json'), true);
        $this->filterBundles = array_keys($bundleList);
    }

    /**
     * @param array $dependency
     *
     * @return bool
     */
    public function filter(array $dependency)
    {
        return in_array($dependency[DependencyTree::META_FOREIGN_BUNDLE], $this->filterBundles);
    }

}
