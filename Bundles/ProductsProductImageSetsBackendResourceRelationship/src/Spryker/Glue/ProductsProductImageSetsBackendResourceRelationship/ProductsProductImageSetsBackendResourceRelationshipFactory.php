<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship;

use Spryker\Glue\Kernel\Backend\AbstractFactory;
use Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Dependency\Resource\ProductsProductImageSetsBackendResourceRelationshipToProductImageSetsBackendApiResourceInterface;
use Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Processor\Expander\ProductConcreteProductImageSetResourceRelationshipExpander;
use Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Processor\Expander\ProductConcreteProductImageSetResourceRelationshipExpanderInterface;
use Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Processor\Filter\ConcreteProductsResourceFilter;
use Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Processor\Filter\ConcreteProductsResourceFilterInterface;
use Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Processor\Reader\ProductConcreteProductImageSetResourceRelationshipReader;
use Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Processor\Reader\ProductConcreteProductImageSetResourceRelationshipReaderInterface;

class ProductsProductImageSetsBackendResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Processor\Expander\ProductConcreteProductImageSetResourceRelationshipExpanderInterface
     */
    public function createProductConcreteProductImageSetResourceRelationshipExpander(): ProductConcreteProductImageSetResourceRelationshipExpanderInterface
    {
        return new ProductConcreteProductImageSetResourceRelationshipExpander(
            $this->createProductConcreteProductImageSetResourceRelationshipReader(),
            $this->createConcreteProductsResourceFilter(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Processor\Filter\ConcreteProductsResourceFilterInterface
     */
    public function createConcreteProductsResourceFilter(): ConcreteProductsResourceFilterInterface
    {
        return new ConcreteProductsResourceFilter();
    }

    /**
     * @return \Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Processor\Reader\ProductConcreteProductImageSetResourceRelationshipReaderInterface
     */
    public function createProductConcreteProductImageSetResourceRelationshipReader(): ProductConcreteProductImageSetResourceRelationshipReaderInterface
    {
        return new ProductConcreteProductImageSetResourceRelationshipReader(
            $this->getProductImageSetsBackendApiResource(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Dependency\Resource\ProductsProductImageSetsBackendResourceRelationshipToProductImageSetsBackendApiResourceInterface
     */
    public function getProductImageSetsBackendApiResource(): ProductsProductImageSetsBackendResourceRelationshipToProductImageSetsBackendApiResourceInterface
    {
        return $this->getProvidedDependency(ProductsProductImageSetsBackendResourceRelationshipDependencyProvider::RESOURCE_PRODUCT_IMAGE_SETS_BACKEND_API);
    }
}
