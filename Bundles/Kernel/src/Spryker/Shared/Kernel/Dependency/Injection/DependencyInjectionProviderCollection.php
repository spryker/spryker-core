<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\Dependency\Injection;

class DependencyInjectionProviderCollection implements DependencyInjectionProviderCollectionInterface
{

    /**
     * @var \Spryker\Shared\Kernel\Dependency\Injection\DependencyInjectionInterface[]
     */
    protected $dependencyInjectionProvider = [];

    /**
     * @param \Spryker\Shared\Kernel\Dependency\Injection\DependencyInjectionInterface $dependencyInjectorProvider
     *
     * @return $this
     */
    public function addDependencyInjectorProvider(DependencyInjectionInterface $dependencyInjectorProvider)
    {
        $this->dependencyInjectionProvider[] = $dependencyInjectorProvider;

        return $this;
    }

    /**
     * @return \Spryker\Shared\Kernel\Dependency\Injection\DependencyInjectionInterface[]
     */
    public function getDependencyInjectionProvider()
    {
        return $this->dependencyInjectionProvider;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->dependencyInjectionProvider);
    }

}
