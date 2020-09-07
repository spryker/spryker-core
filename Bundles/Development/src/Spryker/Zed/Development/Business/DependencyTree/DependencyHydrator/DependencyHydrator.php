<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyHydrator;

class DependencyHydrator implements DependencyHydratorCompositeInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\DependencyHydrator\DependencyHydratorInterface[]
     */
    protected $hydrator;

    /**
     * @param \Spryker\Zed\Development\Business\DependencyTree\DependencyHydrator\DependencyHydratorInterface $hydrator
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
