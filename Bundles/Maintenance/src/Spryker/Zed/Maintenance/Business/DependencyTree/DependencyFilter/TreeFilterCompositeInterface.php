<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter;

interface TreeFilterCompositeInterface extends TreeFilterInterface
{

    /**
     * @param \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\DependencyFilterInterface $filter
     *
     * @return $this
     */
    public function addFilter(DependencyFilterInterface $filter);

}
