<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyHydrator;

interface DependencyHydratorInterface
{
    /**
     * @param array $dependency
     *
     * @return void
     */
    public function hydrate(array &$dependency);
}
