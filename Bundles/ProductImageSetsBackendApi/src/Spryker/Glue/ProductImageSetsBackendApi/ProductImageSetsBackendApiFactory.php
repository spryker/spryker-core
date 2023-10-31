<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractFactory;
use Spryker\Glue\ProductImageSetsBackendApi\Dependency\Facade\ProductImageSetsBackendApiToProductImageFacadeInterface;
use Spryker\Glue\ProductImageSetsBackendApi\Processor\Expander\ProductConcreteProductImageSetResourceRelationshipExpander;
use Spryker\Glue\ProductImageSetsBackendApi\Processor\Expander\ProductConcreteProductImageSetResourceRelationshipExpanderInterface;
use Spryker\Glue\ProductImageSetsBackendApi\Processor\Filter\ConcreteProductsResourceFilter;
use Spryker\Glue\ProductImageSetsBackendApi\Processor\Filter\ConcreteProductsResourceFilterInterface;
use Spryker\Glue\ProductImageSetsBackendApi\Processor\Mapper\ProductImageSetResourceMapper;
use Spryker\Glue\ProductImageSetsBackendApi\Processor\Mapper\ProductImageSetResourceMapperInterface;
use Spryker\Glue\ProductImageSetsBackendApi\Processor\Reader\ProductConcreteProductImageSetResourceRelationshipReader;
use Spryker\Glue\ProductImageSetsBackendApi\Processor\Reader\ProductConcreteProductImageSetResourceRelationshipReaderInterface;
use Spryker\Glue\ProductImageSetsBackendApi\Processor\Reader\ProductImageSetResourceReader;
use Spryker\Glue\ProductImageSetsBackendApi\Processor\Reader\ProductImageSetResourceReaderInterface;

/**
 * @method \Spryker\Glue\ProductImageSetsBackendApi\ProductImageSetsBackendApiConfig getConfig()
 */
class ProductImageSetsBackendApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductImageSetsBackendApi\Processor\Reader\ProductImageSetResourceReaderInterface
     */
    public function createProductImageSetResourceReader(): ProductImageSetResourceReaderInterface
    {
        return new ProductImageSetResourceReader(
            $this->getProductImageFacade(),
            $this->createProductImageSetResourceMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductImageSetsBackendApi\Processor\Expander\ProductConcreteProductImageSetResourceRelationshipExpanderInterface
     */
    public function createProductConcreteProductImageSetResourceRelationshipExpander(): ProductConcreteProductImageSetResourceRelationshipExpanderInterface
    {
        return new ProductConcreteProductImageSetResourceRelationshipExpander(
            $this->createProductConcreteProductImageSetResourceRelationshipReader(),
            $this->createConcreteProductsResourceFilter(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductImageSetsBackendApi\Processor\Reader\ProductConcreteProductImageSetResourceRelationshipReaderInterface
     */
    public function createProductConcreteProductImageSetResourceRelationshipReader(): ProductConcreteProductImageSetResourceRelationshipReaderInterface
    {
        return new ProductConcreteProductImageSetResourceRelationshipReader(
            $this->createProductImageSetResourceReader(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductImageSetsBackendApi\Processor\Filter\ConcreteProductsResourceFilterInterface
     */
    public function createConcreteProductsResourceFilter(): ConcreteProductsResourceFilterInterface
    {
        return new ConcreteProductsResourceFilter();
    }

    /**
     * @return \Spryker\Glue\ProductImageSetsBackendApi\Processor\Mapper\ProductImageSetResourceMapperInterface
     */
    public function createProductImageSetResourceMapper(): ProductImageSetResourceMapperInterface
    {
        return new ProductImageSetResourceMapper();
    }

    /**
     * @return \Spryker\Glue\ProductImageSetsBackendApi\Dependency\Facade\ProductImageSetsBackendApiToProductImageFacadeInterface
     */
    public function getProductImageFacade(): ProductImageSetsBackendApiToProductImageFacadeInterface
    {
        return $this->getProvidedDependency(ProductImageSetsBackendApiDependencyProvider::FACADE_PRODUCT_IMAGE);
    }
}
