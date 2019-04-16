<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\PersistentCartShare\CartShareOption\CartShareOptionReader;
use Spryker\Client\PersistentCartShare\CartShareOption\CartShareOptionReaderInterface;

class PersistentCartShareFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\PersistentCartShare\CartShareOption\CartShareOptionReaderInterface
     */
    public function createCartShareOptionReader(): CartShareOptionReaderInterface
    {
        return new CartShareOptionReader(
            $this->getCartShareOptionPlugins()
        );
    }

    /**
     * @return \Spryker\Client\PersistentCartShareExtension\Dependency\Plugin\CartShareOptionPluginInterface[]
     */
    public function getCartShareOptionPlugins(): array
    {
        return $this->getProvidedDependency(PersistentCartShareDependencyProvider::PLUGINS_CART_SHARE_OPTION);
    }
}
