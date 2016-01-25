<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter;

class BundleToViewFilter extends AbstractDependencyFilter
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
     * @param string $bundle
     * @param array $context
     *
     * @return bool
     */
    public function filter($bundle, array $context)
    {
        return ($bundle !== $this->bundle);
    }

}
