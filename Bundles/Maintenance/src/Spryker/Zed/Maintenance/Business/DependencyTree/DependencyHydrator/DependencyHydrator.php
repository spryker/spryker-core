<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyHydrator;

class DependencyHydrator implements DependencyHydratorCompositeInterface
{

    /**
     * @var \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyHydrator\DependencyHydratorInterface[]
     */
    private $hydrator;

    /**
     * @param \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyHydrator\DependencyHydratorInterface $hydrator
     *
     * @return $this
     */
    public function addHydrator(DependencyHydratorInterface $hydrator)
    {
        $this->hydrator[] = $hydrator;

        return $this;
    }

    /**
     * @param array $dependencyTree
     *
     * @return array
     */
    public function hydrate(array $dependencyTree)
    {
        $hydratedTree = [];
        foreach ($dependencyTree as $dependency) {
            foreach ($this->hydrator as $hydrator) {
                $hydrator->hydrate($dependency);
            }
            $hydratedTree[] = $dependency;
        }

        return $hydratedTree;
    }

}
