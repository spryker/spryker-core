<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\ViolationFinder;

interface ViolationFinderInterface
{

    /**
     * @param array $dependency
     *
     * @return bool
     */
    public function isViolation(array $dependency);

}
