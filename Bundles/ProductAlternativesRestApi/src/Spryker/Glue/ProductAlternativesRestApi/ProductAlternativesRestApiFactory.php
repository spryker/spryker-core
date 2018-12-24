<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAlternativesRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductAlternativeStorageClientInterface;
use Spryker\Glue\ProductAlternativesRestApi\Dependency\Client\ProductAlternativesRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductAlternativesRestApi\Processor\Expander\AlternativeProductResourceRelationshipExpander;
use Spryker\Glue\ProductAlternativesRestApi\Processor\Expander\AlternativeProductResourceRelationshipExpanderInterface;
use Spryker\Glue\ProductAlternativesRestApi\Processor\ProductAlternative\AlternativeProductReader;
use Spryker\Glue\ProductAlternativesRestApi\Processor\ProductAlternative\AlternativeProductReaderInterface;

class ProductAlternativesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductAlternativesRestApi\Processor\ProductAlternative\AlternativeProductReaderInterface
     */
    public function createProductAlternativeReader(): AlternativeProductReaderInterface
    {
        return new AlternativeProductReader(
            $this->getProductAlternativeStorageClient(),
            $this->getProductStorageClient(),
            $this->getResourceBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ProductAlternativesRestApi\Processor\Expander\AlternativeProductResourceRelationshipExpanderInterface
     */
    public function createProductAlternativesResourceRelationshipExpander(): AlternativeProductResourceRelationshipExpanderInterface
    {
        return new AlternativeProductResourceRelationshipExpander($this->createProductAlternativeReader());
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
