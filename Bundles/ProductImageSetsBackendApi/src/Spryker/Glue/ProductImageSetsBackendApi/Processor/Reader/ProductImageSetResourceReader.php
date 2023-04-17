<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsBackendApi\Processor\Reader;

use Generated\Shared\Transfer\ProductImageSetCollectionTransfer;
use Generated\Shared\Transfer\ProductImageSetCriteriaTransfer;
use Generated\Shared\Transfer\ProductImageSetResourceCollectionTransfer;
use Spryker\Glue\ProductImageSetsBackendApi\Dependency\Facade\ProductImageSetsBackendApiToProductImageFacadeInterface;
use Spryker\Glue\ProductImageSetsBackendApi\Processor\Mapper\ProductImageSetResourceMapperInterface;

class ProductImageSetResourceReader implements ProductImageSetResourceReaderInterface
{
    /**
     * @var \Spryker\Glue\ProductImageSetsBackendApi\Dependency\Facade\ProductImageSetsBackendApiToProductImageFacadeInterface
     */
    protected ProductImageSetsBackendApiToProductImageFacadeInterface $productImageFacade;

    /**
     * @var \Spryker\Glue\ProductImageSetsBackendApi\Processor\Mapper\ProductImageSetResourceMapperInterface
     */
    protected ProductImageSetResourceMapperInterface $productImageSetResourceMapper;

    /**
     * @param \Spryker\Glue\ProductImageSetsBackendApi\Dependency\Facade\ProductImageSetsBackendApiToProductImageFacadeInterface $productImageFacade
     * @param \Spryker\Glue\ProductImageSetsBackendApi\Processor\Mapper\ProductImageSetResourceMapperInterface $productImageSetResourceMapper
     */
    public function __construct(
        ProductImageSetsBackendApiToProductImageFacadeInterface $productImageFacade,
        ProductImageSetResourceMapperInterface $productImageSetResourceMapper
    ) {
        $this->productImageFacade = $productImageFacade;
        $this->productImageSetResourceMapper = $productImageSetResourceMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetResourceCollectionTransfer
     */
    public function getConcreteProductImageSetResourceCollection(
        ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
    ): ProductImageSetResourceCollectionTransfer {
        $productImageSetCollectionTransfer = $this->productImageFacade->getConcreteProductImageSetCollection($productImageSetCriteriaTransfer);
        $productImageSetTransfersGroupedBySku = $this->getProductImageSetTransfersGroupedBySku($productImageSetCollectionTransfer);

        return $this->productImageSetResourceMapper->mapProductImageSetCollectionToProductImageSetResourceCollection(
            $productImageSetTransfersGroupedBySku,
            new ProductImageSetResourceCollectionTransfer(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetCollectionTransfer $productImageSetCollectionTransfer
     *
     * @return array<string, list<\Generated\Shared\Transfer\ProductImageSetTransfer>>
     */
    protected function getProductImageSetTransfersGroupedBySku(
        ProductImageSetCollectionTransfer $productImageSetCollectionTransfer
    ): array {
        $groupedProductImageSetTransfers = [];
        foreach ($productImageSetCollectionTransfer->getProductImageSets() as $productImageSetTransfer) {
            $groupedProductImageSetTransfers[$productImageSetTransfer->getSkuOrFail()][] = $productImageSetTransfer;
        }

        return $groupedProductImageSetTransfers;
    }
}
