<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Checkout;

use Spryker\Client\Checkout\Dependency\Client\CheckoutToGlossaryStorageClientBridge;
use Spryker\Client\Checkout\Dependency\Client\CheckoutToLocaleClientBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CheckoutDependencyProvider extends AbstractDependencyProvider
{
    const SERVICE_ZED = 'zed service';

    public const CLIENT_GLOSSARY_STORAGE = 'CLIENT_GLOSSARY_STORAGE';
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[self::SERVICE_ZED] = function (Container $container) {
            return $container->getLocator()->zedRequest()->client();
        };

        $container = $this->addGlossaryStorageClient($container);
        $container = $this->addLocaleClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addGlossaryStorageClient(Container $container): Container
    {
        $container[static::CLIENT_GLOSSARY_STORAGE] = function (Container $container) {
            return new CheckoutToGlossaryStorageClientBridge(
                $container->getLocator()->glossaryStorage()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container[static::CLIENT_LOCALE] = function (Container $container) {
            return new CheckoutToLocaleClientBridge(
                $container->getLocator()->locale()->client()
            );
        };

        return $container;
    }
}
