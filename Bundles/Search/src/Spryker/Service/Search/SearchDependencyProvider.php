<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Search;

use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;
use Spryker\Service\Search\Dependency\Client\SearchToSearchClientBridge;

class SearchDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_SEARCH = 'CLIENT_SEARCH';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    public function provideServiceDependencies(Container $container)
    {
        $container = $this->addStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_SEARCH, function ($container) {
            return new SearchToSearchClientBridge(
                $container->getLocator()->search()->client()
            );
        });

        return $container;
    }
}
