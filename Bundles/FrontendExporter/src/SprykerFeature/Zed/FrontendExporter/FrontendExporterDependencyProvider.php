<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\FrontendExporter;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class FrontendExporterDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_LOCALE = 'locale facade';

    /**
     * @var Container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->provideLocaleFacade($container);

        return $container;
    }

    /**
     * @var Container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->provideLocaleFacade($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    private function provideLocaleFacade(Container $container)
    {
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return $container->getLocator()->locale()->facade();
        };

        return $container;
    }

}
