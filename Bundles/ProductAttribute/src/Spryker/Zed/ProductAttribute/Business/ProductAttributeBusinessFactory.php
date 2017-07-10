<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductAttribute\Business\Model\Product\Mapper\ProductAttributeMapper;
use Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttribute;
use Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttributeReader;
use Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttributeWriter;
use Spryker\Zed\ProductAttribute\ProductAttributeDependencyProvider;

/**
 * @method \Spryker\Zed\ProductAttribute\ProductAttributeConfig getConfig()
 * @method \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainer getQueryContainer()
 */
class ProductAttributeBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttributeInterface
     */
    public function createProductAttributeManager()
    {
        return new ProductAttribute(
            $this->createAttributeReader(),
            $this->createAttributeWriter(),
            $this->createProductAttributeMapper()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttributeReaderInterface
     */
    public function createAttributeReader()
    {
        return new ProductAttributeReader(
            $this->getQueryContainer(),
            $this->getProductQueryContainer(),
            $this->createProductAttributeMapper()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttributeWriterInterface
     */
    public function createAttributeWriter()
    {
        return new ProductAttributeWriter(
            $this->createAttributeReader(),
            $this->createProductAttributeMapper(),
            $this->getLocaleFacade(),
            $this->getProductQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Business\Model\Product\Mapper\ProductAttributeMapperInterface
     */
    public function createProductAttributeMapper()
    {
        return new ProductAttributeMapper(
            $this->getEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected function getProductQueryContainer()
    {
        return $this->getProvidedDependency(ProductAttributeDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductAttributeDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Dependency\Service\ProductAttributeToUtilEncodingInterface
     */
    protected function getEncodingService()
    {
        return $this->getProvidedDependency(ProductAttributeDependencyProvider::SERVICE_UTIL_ENCODING);
    }

}
