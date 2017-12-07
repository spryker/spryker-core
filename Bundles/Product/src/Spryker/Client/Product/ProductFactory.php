<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Product;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Product\KeyBuilder\AttributeMapResourceKeyBuilder;
use Spryker\Client\Product\KeyBuilder\ProductAbstractResourceKeyBuilder;
use Spryker\Client\Product\KeyBuilder\ProductConcreteResourceKeyBuilder;
use Spryker\Client\Product\Mapper\AttributeVariantMapper;
use Spryker\Client\Product\Mapper\StorageProductMapper;
use Spryker\Client\Product\Storage\AttributeMapStorage;
use Spryker\Client\Product\Storage\ProductAbstractStorage;
use Spryker\Client\Product\Storage\ProductConcreteStorage;

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
     * @return \Spryker\Client\Product\Storage\AttributeMapStorageInterface
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
     * @return \Spryker\Client\Product\Storage\ProductConcreteStorageInterface
     */
    public function createProductConcreteStorage($locale)
    {
        return new ProductConcreteStorage(
            $this->getStorage(),
            $this->createProductConcreteKeyBuilder(),
            $this->getUtilEncodingService(),
            $locale
        );
    }

    /**
     * @return \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected function createAttributeMapKeyBuilder()
    {
        return new AttributeMapResourceKeyBuilder();
    }

    /**
     * @return \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected function createProductConcreteKeyBuilder()
    {
        return new ProductConcreteResourceKeyBuilder();
    }

    /**
     * @return \Spryker\Client\Product\Dependency\Client\ProductToStorageInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::KV_STORAGE);
    }

    /**
     * @return \Spryker\Client\Product\Dependency\Service\ProductToUtilEncodingInterface
     */
    protected function getUtilEncodingService()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::SERVICE_ENCODING);
    }

    /**
     * @return \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected function createKeyBuilder()
    {
        return new ProductAbstractResourceKeyBuilder();
    }

    /**
     * @return \Spryker\Client\Product\Dependency\Client\ProductToLocaleInterface
     */
    public function getLocaleClient()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @param string $locale
     *
     * @return \Spryker\Client\Product\Mapper\StorageProductMapperInterface
     */
    public function createStorageProductMapper($locale)
    {
        return new StorageProductMapper($this->createAttributeVariantMapper($locale), $this->getStorageProductExpanderPlugins(), $locale);
    }

    /**
     * @param string $locale
     *
     * @return \Spryker\Client\Product\Mapper\AttributeVariantMapperInterface
     */
    protected function createAttributeVariantMapper($locale)
    {
        return new AttributeVariantMapper($this->createProductConcreteStorage($locale), $this->createAttributeMapStorage($locale));
    }

    /**
     * @return \Spryker\Client\Product\Dependency\Plugin\StorageProductExpanderPluginInterface[]
     */
    protected function getStorageProductExpanderPlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PLUGIN_STORAGE_PRODUCT_EXPANDERS);
    }
}
