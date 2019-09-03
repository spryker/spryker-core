<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductLabelsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductLabelsRestApi\Dependency\Client\ProductLabelsRestApiToProductLabelStorageClientInterface;
use Spryker\Glue\ProductLabelsRestApi\Dependency\Client\ProductLabelsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductLabelsRestApi\Processor\Expander\ProductLabelByConcreteProductSkuExpander;
use Spryker\Glue\ProductLabelsRestApi\Processor\Expander\ProductLabelByConcreteProductSkuExpanderInterface;
use Spryker\Glue\ProductLabelsRestApi\Processor\Expander\ProductLabelResourceRelationshipExpander;
use Spryker\Glue\ProductLabelsRestApi\Processor\Expander\ProductLabelResourceRelationshipExpanderInterface;
use Spryker\Glue\ProductLabelsRestApi\Processor\Mapper\ProductLabelMapper;
use Spryker\Glue\ProductLabelsRestApi\Processor\Mapper\ProductLabelMapperInterface;
use Spryker\Glue\ProductLabelsRestApi\Processor\Reader\ProductLabelReader;
use Spryker\Glue\ProductLabelsRestApi\Processor\Reader\ProductLabelReaderInterface;

class ProductLabelsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductLabelsRestApi\Processor\Reader\ProductLabelReaderInterface
     */
    public function createProductLabelReader(): ProductLabelReaderInterface
    {
        return new ProductLabelReader(
            $this->getProductLabelStorageClient(),
            $this->createProductLabelMapper(),
            $this->getResourceBuilder(),
            $this->getProductStorageClient()
        );
    }

    /**
     * @return \Spryker\Glue\ProductLabelsRestApi\Processor\Mapper\ProductLabelMapperInterface
     */
    public function createProductLabelMapper(): ProductLabelMapperInterface
    {
        return new ProductLabelMapper();
    }

    /**
     * @return \Spryker\Glue\ProductLabelsRestApi\Processor\Expander\ProductLabelResourceRelationshipExpanderInterface
     */
    public function createProductLabelResourceRelationshipExpander(): ProductLabelResourceRelationshipExpanderInterface
    {
        return new ProductLabelResourceRelationshipExpander($this->createProductLabelReader());
    }

    /**
     * @return \Spryker\Glue\ProductLabelsRestApi\Dependency\Client\ProductLabelsRestApiToProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductLabelsRestApiToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductLabelsRestApiDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ProductLabelsRestApi\Dependency\Client\ProductLabelsRestApiToProductLabelStorageClientInterface
     */
    public function getProductLabelStorageClient(): ProductLabelsRestApiToProductLabelStorageClientInterface
    {
        return $this->getProvidedDependency(ProductLabelsRestApiDependencyProvider::CLIENT_PRODUCT_LABEL_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ProductLabelsRestApi\Processor\Expander\ProductLabelByConcreteProductSkuExpanderInterface
     */
    public function createProductLabelByConcreteProductSkuExpander(): ProductLabelByConcreteProductSkuExpanderInterface
    {
        return new ProductLabelByConcreteProductSkuExpander($this->createProductLabelReader());
    }
}
