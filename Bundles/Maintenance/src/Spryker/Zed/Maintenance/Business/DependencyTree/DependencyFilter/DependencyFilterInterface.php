<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter;

interface DependencyFilterInterface
{

    /**
     * @param array $dependency
     *
     * @return bool
     */
    public function filter(array $dependency);

}
