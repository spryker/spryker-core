<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartNote;

use Spryker\Client\CartNote\Dependency\Client\CartNoteToPersistentCartClientBridge;
use Spryker\Client\CartNote\Dependency\Client\CartNoteToQuoteClientBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CartNoteDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';
    public const CLIENT_PERSISTENT_CART = 'CLIENT_PERSISTENT_CART';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addQuoteClient($container);
        $container = $this->addPersistentCartClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addQuoteClient(Container $container)
    {
        $container[static::CLIENT_QUOTE] = function (Container $container) {
            return new CartNoteToQuoteClientBridge($container->getLocator()->quote()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPersistentCartClient(Container $container)
    {
        $container[static::CLIENT_PERSISTENT_CART] = function (Container $container) {
            return new CartNoteToPersistentCartClientBridge($container->getLocator()->persistentCart()->client());
        };

        return $container;
    }
}
