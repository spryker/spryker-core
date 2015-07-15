<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Glossary;

use SprykerEngine\Client\Kernel\AbstractDependencyProvider;
use SprykerEngine\Client\Kernel\Container;

class GlossaryDependencyProvider extends AbstractDependencyProvider
{

    const STORAGE = 'glossary key value storage';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[self::STORAGE] = function (Container $container) {
            return $container->getLocator()->storage()->client();
        };

        return $container;
    }

}
