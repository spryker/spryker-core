<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter;

abstract class AbstractDependencyFilter
{

    /**
     * @param string $bundle
     * @param array $context
     *
     * @return bool
     */
    abstract public function filter($bundle, array $context);

}
