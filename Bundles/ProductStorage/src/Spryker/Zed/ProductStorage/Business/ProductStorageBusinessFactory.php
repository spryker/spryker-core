<?php

namespace Spryker\Zed\ProductStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductStorage\Business\Attribute\AttributeMap;
use Spryker\Zed\ProductStorage\Business\Attribute\AttributeMapInterface;
use Spryker\Zed\ProductStorage\Business\Storage\ProductAbstractStorageWriter;
use Spryker\Zed\ProductStorage\Business\Storage\ProductAbstractStorageWriterInterface;
use Spryker\Zed\ProductStorage\Business\Storage\ProductConcreteStorageWriter;
use Spryker\Zed\ProductStorage\Business\Storage\ProductConcreteStorageWriterInterface;
use Spryker\Zed\ProductStorage\ProductStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductStorage\ProductStorageConfig getConfig()
 * @method \Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainer getQueryContainer()
 */
class ProductStorageBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return ProductAbstractStorageWriterInterface
     */
    public function createProductAbstractStorageWriter()
    {
        return new ProductAbstractStorageWriter(
            $this->getProductFacade(),
            $this->createAttributeMap(),
            $this->getQueryContainer(),
            $this->getStore(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return ProductConcreteStorageWriterInterface
     */
    public function createProductConcreteStorageWriter()
    {
        return new ProductConcreteStorageWriter(
            $this->getProductFacade(),
            $this->getQueryContainer(),
            $this->getStore(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return AttributeMapInterface
     */
    public function createAttributeMap()
    {
        return new AttributeMap(
            $this->getProductFacade(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ProductStorageDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductBridge
     */
    public function getProductFacade()
    {
        return $this->getProvidedDependency(ProductStorageDependencyProvider::FACADE_PRODUCT);
    }
}
