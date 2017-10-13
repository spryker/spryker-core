<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Dependency\Injector;

class DependencyInjectorCollection implements DependencyInjectorCollectionInterface
{
    /**
     * @var \Spryker\Zed\Kernel\Dependency\Injector\DependencyInjectorInterface[]
     */
    protected $dependencyInjector = [];

    /**
     * @param \Spryker\Zed\Kernel\Dependency\Injector\DependencyInjectorInterface $dependencyInjector
     *
     * @return $this
     */
    public function addDependencyInjector(DependencyInjectorInterface $dependencyInjector)
    {
        $this->dependencyInjector[] = $dependencyInjector;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Kernel\Dependency\Injector\DependencyInjectorInterface[]
     */
    public function getDependencyInjector()
    {
        return $this->dependencyInjector;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->dependencyInjector);
    }
}
