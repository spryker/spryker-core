<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Dependency\Injector;

use Spryker\Zed\Kernel\Container;

class DependencyInjector implements DependencyInjectorInterface
{
    /**
     * @var \Spryker\Zed\Kernel\Dependency\Injector\DependencyInjectorCollectionInterface
     */
    private $dependencyInjectorCollection;

    /**
     * @param \Spryker\Zed\Kernel\Dependency\Injector\DependencyInjectorCollectionInterface $dependencyInjectorCollection
     */
    public function __construct(DependencyInjectorCollectionInterface $dependencyInjectorCollection)
    {
        $this->dependencyInjectorCollection = $dependencyInjectorCollection;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function injectBusinessLayerDependencies(Container $container)
    {
        foreach ($this->dependencyInjectorCollection->getDependencyInjector() as $dependencyInjector) {
            $container = $dependencyInjector->injectBusinessLayerDependencies($container);
        }

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function injectCommunicationLayerDependencies(Container $container)
    {
        foreach ($this->dependencyInjectorCollection->getDependencyInjector() as $dependencyInjector) {
            $container = $dependencyInjector->injectCommunicationLayerDependencies($container);
        }

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function injectPersistenceLayerDependencies(Container $container)
    {
        foreach ($this->dependencyInjectorCollection->getDependencyInjector() as $dependencyInjector) {
            $container = $dependencyInjector->injectPersistenceLayerDependencies($container);
        }

        return $container;
    }
}
