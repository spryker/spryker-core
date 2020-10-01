<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductAttributesRestApi\Dependency\Client\ProductAttributesRestApiToProductAttributeClientInterface;
use Spryker\Glue\ProductAttributesRestApi\Processor\Builder\RestProductAttributeResponseBuilder;
use Spryker\Glue\ProductAttributesRestApi\Processor\Builder\RestProductAttributeResponseBuilderInterface;
use Spryker\Glue\ProductAttributesRestApi\Processor\Mapper\ProductAttributeMapper;
use Spryker\Glue\ProductAttributesRestApi\Processor\Mapper\ProductAttributeMapperInterface;
use Spryker\Glue\ProductAttributesRestApi\Processor\Reader\ProductAttributeReader;
use Spryker\Glue\ProductAttributesRestApi\Processor\Reader\ProductAttributeReaderInterface;

/**
 * @method \Spryker\Glue\ProductAttributesRestApi\ProductAttributesRestApiConfig getConfig()
 */
class ProductAttributesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductAttributesRestApi\Processor\Reader\ProductAttributeReaderInterface
     */
    public function createProductAttributeReader(): ProductAttributeReaderInterface
    {
        return new ProductAttributeReader(
            $this->getProductAttributeClient(),
            $this->createRestProductAttributeResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ProductAttributesRestApi\Processor\Builder\RestProductAttributeResponseBuilderInterface
     */
    public function createRestProductAttributeResponseBuilder(): RestProductAttributeResponseBuilderInterface
    {
        return new RestProductAttributeResponseBuilder(
            $this->getResourceBuilder(),
            $this->createProductAttributeMapper()
        );
    }

    /**
     * @return \Spryker\Glue\ProductAttributesRestApi\Processor\Mapper\ProductAttributeMapperInterface
     */
    public function createProductAttributeMapper(): ProductAttributeMapperInterface
    {
        return new ProductAttributeMapper();
    }

    /**
     * @return \Spryker\Glue\ProductAttributesRestApi\Dependency\Client\ProductAttributesRestApiToProductAttributeClientInterface
     */
    public function getProductAttributeClient(): ProductAttributesRestApiToProductAttributeClientInterface
    {
        return $this->getProvidedDependency(ProductAttributesRestApiDependencyProvider::CLIENT_PRODUCT_ATTRIBUTE);
    }
}
