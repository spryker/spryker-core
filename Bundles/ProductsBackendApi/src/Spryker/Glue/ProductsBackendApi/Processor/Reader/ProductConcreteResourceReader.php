<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsBackendApi\Processor\Reader;

use Generated\Shared\Transfer\ProductConcreteCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteResourceCollectionTransfer;
use Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToProductFacadeInterface;
use Spryker\Glue\ProductsBackendApi\Processor\Mapper\ProductConcreteResourceMapperInterface;

class ProductConcreteResourceReader implements ProductConcreteResourceReaderInterface
{
    /**
     * @var \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToProductFacadeInterface
     */
    protected ProductsBackendApiToProductFacadeInterface $productFacade;

    /**
     * @var \Spryker\Glue\ProductsBackendApi\Processor\Mapper\ProductConcreteResourceMapperInterface
     */
    protected ProductConcreteResourceMapperInterface $productConcreteResourceMapper;

    /**
     * @param \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToProductFacadeInterface $productFacade
     * @param \Spryker\Glue\ProductsBackendApi\Processor\Mapper\ProductConcreteResourceMapperInterface $productConcreteResourceMapper
     */
    public function __construct(
        ProductsBackendApiToProductFacadeInterface $productFacade,
        ProductConcreteResourceMapperInterface $productConcreteResourceMapper
    ) {
        $this->productFacade = $productFacade;
        $this->productConcreteResourceMapper = $productConcreteResourceMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaTransfer $productConcreteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteResourceCollectionTransfer
     */
    public function getProductConcreteResourceCollection(
        ProductConcreteCriteriaTransfer $productConcreteCriteriaTransfer
    ): ProductConcreteResourceCollectionTransfer {
        $productConcreteCollectionTransfer = $this->productFacade->getProductConcreteCollection($productConcreteCriteriaTransfer);

        return $this->productConcreteResourceMapper->mapProductConcreteCollectionTransferToProductConcreteResourceCollectionTransfer(
            $productConcreteCollectionTransfer,
            new ProductConcreteResourceCollectionTransfer(),
        );
    }
}
