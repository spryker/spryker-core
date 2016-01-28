<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter;

interface DependencyFilterCompositeInterface extends DependencyFilterInterface
{

    /**
     * @param DependencyFilterInterface $dependencyFilter
     *
     * @return self
     */
    public function addFilter(DependencyFilterInterface $dependencyFilter);

}
