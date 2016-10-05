<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Product;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Product\KeyBuilder\AttributeMapResourceKeyBuilder;
use Spryker\Client\Product\KeyBuilder\ProductConcreteResourceKeyBuilder;
use Spryker\Client\Product\KeyBuilder\ProductAbstractResourceKeyBuilder;
use Spryker\Client\Product\Storage\AttributeMapStorage;
use Spryker\Client\Product\Storage\ProductConcreteStorage;
use Spryker\Client\Product\Storage\ProductAbstractStorage;

class ProductFactory extends AbstractFactory
{

    /**
     * @param string $locale
     *
     * @return \Spryker\Client\Product\Storage\ProductAbstractStorageInterface
     */
    public function createProductAbstractStorage($locale)
    {
        return new ProductAbstractStorage(
            $this->getStorage(),
            $this->createKeyBuilder(),
            $locale
        );
    }

    /**
     * @param string $locale
     *
     * @return \Spryker\Client\Product\Storage\AttributeMapStorage
     */
    public function createAttributeMapStorage($locale)
    {
        return new AttributeMapStorage(
            $this->getStorage(),
            $this->createAttributeMapKeyBuilder(),
            $locale
        );
    }

    /**
     * @param string $locale
     *
     * @return \Spryker\Client\Product\Storage\ProductConcreteStorage
     */
    public function createProductConcreteStorage($locale)
    {
        return new ProductConcreteStorage(
            $this->getStorage(),
            $this->createProductConcreteKeyBuilder(),
            $locale
        );
    }

    /**
     * @return \Spryker\Client\Product\KeyBuilder\AttributeMapResourceKeyBuilder
     */
    protected function createAttributeMapKeyBuilder()
    {
        return new AttributeMapResourceKeyBuilder();
    }

    /**
     * @return \Spryker\Client\Product\KeyBuilder\ProductConcreteResourceKeyBuilder
     */
    protected function createProductConcreteKeyBuilder()
    {
        return new ProductConcreteResourceKeyBuilder();
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::KV_STORAGE);
    }

    /**
     * @return \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface
     */
    protected function createKeyBuilder()
    {
        return new ProductAbstractResourceKeyBuilder();
    }

    /**
     * @return \Spryker\Client\Locale\LocaleClient
     */
    public function getLocaleClient()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::CLIENT_LOCALE);
    }

}
