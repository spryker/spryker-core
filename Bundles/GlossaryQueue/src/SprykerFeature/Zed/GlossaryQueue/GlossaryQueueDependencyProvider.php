<?php

namespace SprykerFeature\Zed\GlossaryQueue;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class GlossaryQueueDependencyProvider extends AbstractBundleDependencyProvider
{

    const GLOSSARY_FACADE = 'glossary facade';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::GLOSSARY_FACADE] = function (Container $container) {
            return $container->getLocator()->glossary()->facade();
        };

        return $container;
    }

}
