<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSearch;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductSearch\Dependency\Client\ProductSearchToLocaleClientInterface;
use Spryker\Client\ProductSearch\Dependency\Client\ProductSearchToStorageClientInterface;
use Spryker\Client\ProductSearch\Dependency\Client\ProductSearchToStoreClientInterface;
use Spryker\Shared\ProductSearch\Code\KeyBuilder\ProductSearchConfigExtensionKeyBuilder;

class ProductSearchFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    public function createProductSearchConfigExtensionKeyBuilder()
    {
        return new ProductSearchConfigExtensionKeyBuilder();
    }

    /**
     * @return \Spryker\Client\ProductSearch\Dependency\Client\ProductSearchToStorageClientInterface
     */
    public function getStorageClient(): ProductSearchToStorageClientInterface
    {
        return $this->getProvidedDependency(ProductSearchDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductSearch\Dependency\Client\ProductSearchToStoreClientInterface
     */
    public function getStoreClient(): ProductSearchToStoreClientInterface
    {
        return $this->getProvidedDependency(ProductSearchDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Client\ProductSearch\Dependency\Client\ProductSearchToLocaleClientInterface
     */
    public function getLocaleClient(): ProductSearchToLocaleClientInterface
    {
        return $this->getProvidedDependency(ProductSearchDependencyProvider::CLIENT_LOCALE);
    }
}
