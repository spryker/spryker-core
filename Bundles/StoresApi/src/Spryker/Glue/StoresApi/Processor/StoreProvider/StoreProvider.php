<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi\Processor\StoreProvider;

use Spryker\Glue\StoresApi\Processor\Resolver\StoreResolverInterface;
use Spryker\Service\Container\ContainerInterface;

class StoreProvider implements StoreProviderInterface
{
    /**
     * @var string
     */
    protected const SERVICE_STORE = 'store';

    /**
     * @var \Spryker\Glue\StoresApi\Processor\Resolver\StoreResolverInterface
     */
    protected StoreResolverInterface $storeResolver;

    /**
     * @param \Spryker\Glue\StoresApi\Processor\Resolver\StoreResolverInterface $storeResolver
     */
    public function __construct(StoreResolverInterface $storeResolver)
    {
        $this->storeResolver = $storeResolver;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $storeName = $this->storeResolver->resolveStoreName();

        $container->set(static::SERVICE_STORE, function (ContainerInterface $container) use ($storeName) {
            return $storeName;
        });

        return $container;
    }
}
