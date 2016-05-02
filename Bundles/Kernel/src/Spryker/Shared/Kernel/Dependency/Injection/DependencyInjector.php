<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\Dependency\Injection;

use Spryker\Shared\Kernel\ContainerInterface;

class DependencyInjector implements DependencyInjectionInterface
{

    /**
     * @var \Spryker\Shared\Kernel\Dependency\Injection\DependencyInjectionProviderCollection
     */
    private $dependencyInjectionProviderCollection;

    /**
     * @param \Spryker\Shared\Kernel\Dependency\Injection\DependencyInjectionProviderCollectionInterface $dependencyInjectionProviderCollection
     */
    public function __construct(DependencyInjectionProviderCollectionInterface $dependencyInjectionProviderCollection)
    {
        $this->dependencyInjectionProviderCollection = $dependencyInjectionProviderCollection;
    }

    /**
     * @param \Spryker\Shared\Kernel\ContainerInterface $container
     *
     * @return \Spryker\Shared\Kernel\ContainerInterface
     */
    public function inject(ContainerInterface $container)
    {
        foreach ($this->dependencyInjectionProviderCollection->getDependencyInjectionProvider() as $dependencyInjectionProvider) {
            $container = $dependencyInjectionProvider->inject($container);
        }

        return $container;
    }

}
