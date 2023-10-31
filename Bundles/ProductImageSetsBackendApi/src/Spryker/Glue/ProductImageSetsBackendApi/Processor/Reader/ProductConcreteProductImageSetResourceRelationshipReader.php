<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsBackendApi\Processor\Reader;

use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\ProductImageSetConditionsTransfer;
use Generated\Shared\Transfer\ProductImageSetCriteriaTransfer;
use Generated\Shared\Transfer\ProductImageSetResourceCollectionTransfer;

class ProductConcreteProductImageSetResourceRelationshipReader implements ProductConcreteProductImageSetResourceRelationshipReaderInterface
{
    /**
     * @var \Spryker\Glue\ProductImageSetsBackendApi\Processor\Reader\ProductImageSetResourceReaderInterface
     */
    protected ProductImageSetResourceReaderInterface $imageSetResourceReader;

    /**
     * @param \Spryker\Glue\ProductImageSetsBackendApi\Processor\Reader\ProductImageSetResourceReaderInterface $imageSetResourceReader
     */
    public function __construct(ProductImageSetResourceReaderInterface $imageSetResourceReader)
    {
        $this->imageSetResourceReader = $imageSetResourceReader;
    }

    /**
     * @param list<string> $productConcreteSkus
     * @param string|null $localeName
     *
     * @return array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer>
     */
    public function getProductImageSetRelationshipsIndexedBySku(array $productConcreteSkus, ?string $localeName = null): array
    {
        $productImageSetResourceCollectionTransfer = $this->getConcreteProductImageSetResourceCollection($productConcreteSkus, $localeName);

        return $this->getConcreteProductImageSetResourcesIndexedBySku($productImageSetResourceCollectionTransfer);
    }

    /**
     * @param list<string> $productConcreteSkus
     * @param string|null $localeName
     *
     * @return \Generated\Shared\Transfer\ProductImageSetResourceCollectionTransfer
     */
    protected function getConcreteProductImageSetResourceCollection(array $productConcreteSkus, ?string $localeName = null): ProductImageSetResourceCollectionTransfer
    {
        $productImageSetConditionsTransfer = (new ProductImageSetConditionsTransfer())
            ->setSkus($productConcreteSkus);

        if ($localeName) {
            $productImageSetConditionsTransfer->addLocaleName($localeName);
        }

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())
            ->setProductImageSetConditions($productImageSetConditionsTransfer);

        return $this->imageSetResourceReader->getConcreteProductImageSetResourceCollection($productImageSetCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetResourceCollectionTransfer $productImageSetResourceCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer>
     */
    protected function getConcreteProductImageSetResourcesIndexedBySku(
        ProductImageSetResourceCollectionTransfer $productImageSetResourceCollectionTransfer
    ): array {
        $indexedProductImageSetRelationshipTransfers = [];
        foreach ($productImageSetResourceCollectionTransfer->getProductImageSetResources() as $productImageSetResource) {
            $indexedProductImageSetRelationshipTransfers[$productImageSetResource->getIdOrFail()] = (new GlueRelationshipTransfer())
                ->addResource($productImageSetResource);
        }

        return $indexedProductImageSetRelationshipTransfers;
    }
}
