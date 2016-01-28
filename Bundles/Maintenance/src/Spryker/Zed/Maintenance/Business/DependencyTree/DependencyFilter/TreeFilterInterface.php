<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter;

interface TreeFilterInterface
{
    /**
     * @param array $dependencyTree
     *
     * @return array
     */
    public function filter(array $dependencyTree);
}
