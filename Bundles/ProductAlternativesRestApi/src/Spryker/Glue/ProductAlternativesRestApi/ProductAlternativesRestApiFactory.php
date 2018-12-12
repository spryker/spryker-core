<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAlternativesRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductAlternativeStorageClientInterface;
use Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductAlternativesRestApi\Processor\Expander\ProductAvailabilityResourceRelationshipExpander;
use Spryker\Glue\ProductAlternativesRestApi\Processor\Expander\ProductAvailabilityResourceRelationshipExpanderInterface;
use Spryker\Glue\ProductAlternativesRestApi\Processor\Mapper\ProductAlternativeMapper;
use Spryker\Glue\ProductAlternativesRestApi\Processor\Mapper\ProductAlternativeMapperInterface;
use Spryker\Glue\ProductAlternativesRestApi\Processor\ProductAlternative\ProductAlternativeReader;
use Spryker\Glue\ProductAlternativesRestApi\Processor\ProductAlternative\ProductAlternativeReaderInterface;

class ProductAlternativesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductAlternativesRestApi\Processor\ProductAlternative\ProductAlternativeReaderInterface
     */
    public function createProductAlternativeReader(): ProductAlternativeReaderInterface
    {
        return new ProductAlternativeReader(
            $this->getProductAlternativeStorageClient(),
            $this->getProductStorageClient(),
            $this->createProductAlternativeMapper(),
            $this->getResourceBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ProductAlternativesRestApi\Processor\Mapper\ProductAlternativeMapperInterface
     */
    public function createProductAlternativeMapper(): ProductAlternativeMapperInterface
    {
        return new ProductAlternativeMapper();
    }

    /**
     * @return \Spryker\Glue\ProductAlternativesRestApi\Processor\Expander\ProductAvailabilityResourceRelationshipExpanderInterface
     */
    public function createProductAvailabilityResourceRelationshipExpander(): ProductAvailabilityResourceRelationshipExpanderInterface
    {
        return new ProductAvailabilityResourceRelationshipExpander($this->createProductAlternativeReader());
    }

    /**
     * @return \Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductAlternativeStorageClientInterface
     */
    public function getProductAlternativeStorageClient(): ProductAlternativesRestApiToProductAlternativeStorageClientInterface
    {
        return $this->getProvidedDependency(ProductAlternativesRestApiDependencyProvider::CLIENT_PRODUCT_ALTERNATIVE_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductAlternativesRestApiToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductAlternativesRestApiDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }
}
