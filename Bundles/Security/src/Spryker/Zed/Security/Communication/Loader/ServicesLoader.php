<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Security\Communication\Loader;

use Spryker\Service\Container\ContainerInterface;

class ServicesLoader implements ServicesLoaderInterface
{
    /**
     * @var array<\Spryker\Zed\Security\Communication\Loader\Services\ServiceLoaderInterface>
     */
    protected array $serviceLoaders;

    /**
     * @param array<\Spryker\Zed\Security\Communication\Loader\Services\ServiceLoaderInterface> $serviceLoaders
     */
    public function __construct(array $serviceLoaders)
    {
        $this->serviceLoaders = $serviceLoaders;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        foreach ($this->serviceLoaders as $serviceLoader) {
            $container = $serviceLoader->add($container);
        }

        return $container;
    }
}
