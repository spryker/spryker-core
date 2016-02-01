<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Search;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductSearch\Communication\Plugin\Installer;
use Spryker\Zed\Search\Dependency\Facade\SearchToCollectorBridge;

class SearchDependencyProvider extends AbstractBundleDependencyProvider
{

    const CLIENT_SEARCH = 'search client';
    const FACADE_COLLECTOR = 'collector facade';
    const INSTALLERS = 'installers';

    /**
     * @param Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $this->addSearchClient($container);

        $container[self::INSTALLERS] = function ($container) {
            return $this->getInstallers($container);
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
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
     * @param Container $container
     *
     * @return void
     */
    protected function addCollectorFacade(Container $container)
    {
        $container[self::FACADE_COLLECTOR] = function (Container $container) {
            return new SearchToCollectorBridge($container->getLocator()->collector()->facade());
        };
    }

    /**
     * @param Container $container
     *
     * @return array
     */
    protected function getInstallers(Container $container)
    {
        return [
            new Installer(),
        ];
    }

}
