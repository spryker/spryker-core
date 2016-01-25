<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter;

class EngineBundleFilter extends AbstractDependencyFilter
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
     * @param string $bundle
     * @param array $context
     *
     * @return bool
     */
    public function filter($bundle, array $context)
    {
        return in_array($context['foreign bundle'], $this->filterBundles);
    }

}
