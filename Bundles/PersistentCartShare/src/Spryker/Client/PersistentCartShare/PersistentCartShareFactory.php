<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\PersistentCartShare\CartShareOption\CartShareOptionReader;
use Spryker\Client\PersistentCartShare\CartShareOption\CartShareOptionReaderInterface;
use Spryker\Client\PersistentCartShare\Dependency\Client\PersistentCartShareToResourceShareClientInterface;
use Spryker\Client\PersistentCartShare\Dependency\Client\PersistentCartShareToZedRequestClientInterface;
use Spryker\Client\PersistentCartShare\Quote\QuoteReader;
use Spryker\Client\PersistentCartShare\Quote\QuoteReaderInterface;
use Spryker\Client\PersistentCartShare\Zed\PersistentCartShareStub;

/**
 * @method \Spryker\Client\PersistentCart\PersistentCartConfig getConfig()
 */
class PersistentCartShareFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\PersistentCartShare\Quote\QuoteReaderInterface
     */
    public function createQuoteReader(): QuoteReaderInterface
    {
        return new QuoteReader(
            $this->createZedPersistentCartShareStub()
        );
    }

    /**
     * @return \Spryker\Client\PersistentCartShare\Zed\PersistentCartShareStub
     */
    public function createZedPersistentCartShareStub(): PersistentCartShareStub
    {
        return new PersistentCartShareStub($this->getZedRequestClient());
    }

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

    /**
     * @return \Spryker\Client\PersistentCartShare\Dependency\Client\PersistentCartShareToResourceShareClientInterface
     */
    public function getResourceShareClient(): PersistentCartShareToResourceShareClientInterface
    {
        return $this->getProvidedDependency(PersistentCartShareDependencyProvider::CLIENT_RESOURCE_SHARE);
    }

    /**
     * @return \Spryker\Client\PersistentCartShare\Dependency\Client\PersistentCartShareToZedRequestClientInterface
     */
    public function getZedRequestClient(): PersistentCartShareToZedRequestClientInterface
    {
        return $this->getProvidedDependency(PersistentCartShareDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
