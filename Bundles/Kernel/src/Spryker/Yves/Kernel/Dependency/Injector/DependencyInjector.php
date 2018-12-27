<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Dependency\Injector;

use Spryker\Shared\Kernel\Dependency\Injector\DependencyInjectorCollectionInterface;
use Spryker\Yves\Kernel\Container;

class DependencyInjector implements DependencyInjectorInterface
{
    /**
     * @var \Spryker\Shared\Kernel\Dependency\Injector\DependencyInjectorCollectionInterface
     */
    private $dependencyInjectorCollection;

    /**
     * @param \Spryker\Shared\Kernel\Dependency\Injector\DependencyInjectorCollectionInterface $dependencyInjectorCollection
     */
    public function __construct(DependencyInjectorCollectionInterface $dependencyInjectorCollection)
    {
        $this->dependencyInjectorCollection = $dependencyInjectorCollection;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function injectDependencies(Container $container): Container
    {
        foreach ($this->dependencyInjectorCollection->getDependencyInjector() as $dependencyInjector) {
            $container = $dependencyInjector->inject($container);
        }

        return $container;
    }
}
