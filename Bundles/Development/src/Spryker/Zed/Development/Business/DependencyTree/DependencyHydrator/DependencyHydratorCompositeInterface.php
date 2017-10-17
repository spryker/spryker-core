<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyHydrator;

interface DependencyHydratorCompositeInterface
{
    /**
     * @param \Spryker\Zed\Development\Business\DependencyTree\DependencyHydrator\DependencyHydratorInterface $hydrator
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
