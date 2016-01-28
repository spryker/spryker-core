<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;

class BundleToViewFilter implements DependencyFilterInterface
{

    /**
     * @var string
     */
    private $bundle;

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
        return ($dependency[DependencyTree::META_BUNDLE] !== $this->bundle);
    }

}
