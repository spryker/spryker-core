<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SearchRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\SearchRestApi\Dependency\Client\SearchRestApiToSearchClientBridge;

class SearchRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_SEARCH_CLIENT = 'CLIENT_SEARCH_CLIENT';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addSearchClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addSearchClient(Container $container): Container
    {
        $container[static::CLIENT_SEARCH_CLIENT] = function (Container $container) {
            return new SearchRestApiToSearchClientBridge($container->getLocator()->search()->client());
        };

        return $container;
    }
}
