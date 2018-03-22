<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductGroup;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductGroup\Storage\ProductAbstractGroupStorageReader;
use Spryker\Client\ProductGroup\Storage\ProductGroupStorageReader;
use Spryker\Client\ProductGroup\Storage\ProductStorageReader;
use Spryker\Shared\ProductGroup\KeyBuilder\ProductAbstractGroupsKeyBuilder;
use Spryker\Shared\ProductGroup\KeyBuilder\ProductGroupKeyBuilder;

class ProductGroupFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductGroup\Storage\ProductStorageReaderInterface
     */
    public function createProductStorageReader()
    {
        return new ProductStorageReader(
            $this->createProductAbstractGroupStorageReader(),
            $this->createProductGroupStorageReader(),
            $this->getProductClient()
        );
    }

    /**
     * @return \Spryker\Client\ProductGroup\Storage\ProductAbstractGroupStorageReaderInterface
     */
    public function createProductAbstractGroupStorageReader()
    {
        return new ProductAbstractGroupStorageReader($this->getStorageClient(), $this->createProductAbstractGroupsKeyBuilder());
    }

    /**
     * @return \Spryker\Client\ProductGroup\Storage\ProductGroupStorageReaderInterface
     */
    public function createProductGroupStorageReader()
    {
        return new ProductGroupStorageReader($this->getStorageClient(), $this->createProductGroupKeyBuilder());
    }

    /**
     * @return \Spryker\Client\ProductGroup\Dependency\Client\ProductGroupToStorageInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(ProductGroupDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductGroup\Dependency\Client\ProductGroupToProductInterface
     */
    protected function getProductClient()
    {
        return $this->getProvidedDependency(ProductGroupDependencyProvider::CLIENT_PRODUCT);
    }

    /**
     * @return \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected function createProductAbstractGroupsKeyBuilder()
    {
        return new ProductAbstractGroupsKeyBuilder();
    }

    /**
     * @return \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected function createProductGroupKeyBuilder()
    {
        return new ProductGroupKeyBuilder();
    }
}
