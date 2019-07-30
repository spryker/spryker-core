<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel\Dependency\Injector;

use Spryker\Glue\Kernel\Container;

class DependencyInjector implements DependencyInjectorInterface
{
    /**
     * @var \Spryker\Glue\Kernel\Dependency\Injector\DependencyInjectorCollectionInterface
     */
    private $dependencyInjectorCollection;

    /**
     * @param \Spryker\Glue\Kernel\Dependency\Injector\DependencyInjectorCollectionInterface $dependencyInjectorCollection
     */
    public function __construct(DependencyInjectorCollectionInterface $dependencyInjectorCollection)
    {
        $this->dependencyInjectorCollection = $dependencyInjectorCollection;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function inject(Container $container): Container
    {
        foreach ($this->dependencyInjectorCollection->getDependencyInjector() as $dependencyInjector) {
            $container = $dependencyInjector->inject($container);
        }

        return $container;
    }
}
