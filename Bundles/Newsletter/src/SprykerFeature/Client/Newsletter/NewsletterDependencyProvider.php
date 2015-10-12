<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Newsletter;

use SprykerEngine\Client\Kernel\AbstractDependencyProvider;
use SprykerEngine\Client\Kernel\Container;

class NewsletterDependencyProvider extends AbstractDependencyProvider
{
    const SERVICE_ZED = 'zed service';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[self::SERVICE_ZED] = function (Container $container) {
            return $container->getLocator()->zedRequest()->client();
        };

        return $container;
    }
}
