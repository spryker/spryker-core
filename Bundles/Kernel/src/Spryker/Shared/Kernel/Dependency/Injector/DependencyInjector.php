<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\Dependency\Injector;

use Spryker\Shared\Kernel\ContainerInterface;

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
     * @param \Spryker\Shared\Kernel\ContainerInterface $container
     *
     * @return \Spryker\Shared\Kernel\ContainerInterface
     */
    public function inject(ContainerInterface $container)
    {
        foreach ($this->dependencyInjectorCollection->getDependencyInjector() as $dependencyInjector) {
            $container = $dependencyInjector->inject($container);
        }

        return $container;
    }
}
