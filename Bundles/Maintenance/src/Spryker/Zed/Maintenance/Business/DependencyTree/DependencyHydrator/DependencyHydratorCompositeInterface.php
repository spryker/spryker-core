<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyHydrator;

interface DependencyHydratorCompositeInterface
{

    /**
     * @param \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyHydrator\DependencyHydratorInterface $hydrator
     *
     * @return $this
     */
    public function addHydrator(DependencyHydratorInterface $hydrator);

    /**
     * @param array $dependencyTree
     *
     * @return array
     */
    public function hydrate(array $dependencyTree);

}
