<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Search\Exception\MissingSearchConfigPluginException;
use Spryker\Client\Search\Dependency\Plugin\SearchConfigBuilderInterface;

class SearchDependencyProvider extends AbstractDependencyProvider
{

    const SEARCH_CONFIG_BUILDER = 'search config builder';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container[self::SEARCH_CONFIG_BUILDER] = function (Container $container) {
            return $this->createSearchConfigPlugin($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @throws \Spryker\Client\Search\Exception\MissingSearchConfigPluginException
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigBuilderInterface
     */
    protected function createSearchConfigPlugin(Container $container)
    {
        throw new MissingSearchConfigPluginException(sprintf(
            'Missing instance of %s! You need to implement your own plugin and instantiate it in your own SearchDependencyProvider::createSearchConfigBuilder() to be able to search.',
            SearchConfigBuilderInterface::class
        ));
    }

}
