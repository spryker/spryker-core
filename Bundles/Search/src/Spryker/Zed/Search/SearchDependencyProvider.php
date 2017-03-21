<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Search\Dependency\Service\SearchToUtilEncodingBridge;

class SearchDependencyProvider extends AbstractBundleDependencyProvider
{

    const CLIENT_SEARCH = 'search client';
    const SERVICE_UTIL_ENCODING = 'util encoding service';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $this->addSearchClient($container);
        $this->addUtilEncodingFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addSearchClient(Container $container)
    {
        $container[self::CLIENT_SEARCH] = function (Container $container) {
            return $container->getLocator()->search()->client();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addUtilEncodingFacade(Container $container)
    {
        $container[self::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new SearchToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        };
    }

}
