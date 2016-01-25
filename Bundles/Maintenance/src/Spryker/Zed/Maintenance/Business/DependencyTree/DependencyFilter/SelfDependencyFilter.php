<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter;

class SelfDependencyFilter extends AbstractDependencyFilter
{

    /**
     * @param string $bundle
     * @param array $context
     *
     * @return bool
     */
    public function filter($bundle, array $context)
    {
        return ($bundle === $context['bundle']);
    }

}
