<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Search;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class SearchDependencyProvider extends AbstractBundleDependencyProvider
{

    const CLIENT_SEARCH = 'search client';
    const FACADE_COLLECTOR = 'collector facade';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $this->addSearchClient($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $this->addSearchClient($container);
        $this->addCollectorFacade($container);

        return $container;
    }

    /**
     * @param Container $container
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
     * @param $container
     *
     * @return void
     */
    protected function addCollectorFacade($container)
    {
        $container[self::FACADE_COLLECTOR] = function (Container $container) {
            return $container->getLocator()->collector()->facade();
        };
    }

}
