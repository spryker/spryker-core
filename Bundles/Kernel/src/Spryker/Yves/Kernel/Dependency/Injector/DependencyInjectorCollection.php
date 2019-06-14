<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Dependency\Injector;

class DependencyInjectorCollection implements DependencyInjectorCollectionInterface
{
    /**
     * @var \Spryker\Yves\Kernel\Dependency\Injector\DependencyInjectorInterface[]
     */
    protected $dependencyInjector = [];

    /**
     * @param \Spryker\Yves\Kernel\Dependency\Injector\DependencyInjectorInterface $dependencyInjector
     *
     * @return \Spryker\Yves\Kernel\Dependency\Injector\DependencyInjectorCollectionInterface
     */
    public function addDependencyInjector(DependencyInjectorInterface $dependencyInjector)
    {
        $this->dependencyInjector[] = $dependencyInjector;

        return $this;
    }

    /**
     * @return \Spryker\Yves\Kernel\Dependency\Injector\DependencyInjectorInterface[]
     */
    public function getDependencyInjector(): array
    {
        return $this->dependencyInjector;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->dependencyInjector);
    }
}
