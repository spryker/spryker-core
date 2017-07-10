<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductAttribute\Business\Model\AttributeMapper;
use Spryker\Zed\ProductAttribute\Business\Model\AttributeReader;
use Spryker\Zed\ProductAttribute\Business\Model\AttributeWriter;
use Spryker\Zed\ProductAttribute\Business\Model\ProductAttribute;
use Spryker\Zed\ProductAttribute\ProductAttributeDependencyProvider;

/**
 * @method \Spryker\Zed\ProductAttribute\ProductAttributeConfig getConfig()
 * @method \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainer getQueryContainer()
 */
class ProductAttributeBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductAttribute\Business\Model\ProductAttributeInterface
     */
    public function createProductAttributeManager()
    {
        return new ProductAttribute(
            $this->createAttributeReader(),
            $this->createAttributeWriter(),
            $this->createAttributeMapper()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Business\Model\AttributeReaderInterface
     */
    public function createAttributeReader()
    {
        return new AttributeReader(
            $this->getQueryContainer(),
            $this->getProductQueryContainer(),
            $this->createAttributeMapper()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Business\Model\AttributeWriterInterface
     */
    public function createAttributeWriter()
    {
        return new AttributeWriter(
            $this->createAttributeReader(),
            $this->createAttributeMapper(),
            $this->getLocaleFacade(),
            $this->getProductQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Business\Model\AttributeMapperInterface
     */
    public function createAttributeMapper()
    {
        return new AttributeMapper(
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
