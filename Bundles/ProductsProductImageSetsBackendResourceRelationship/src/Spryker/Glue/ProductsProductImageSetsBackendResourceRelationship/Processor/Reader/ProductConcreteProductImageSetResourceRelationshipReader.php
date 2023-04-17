<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Processor\Reader;

use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\ProductImageSetConditionsTransfer;
use Generated\Shared\Transfer\ProductImageSetCriteriaTransfer;
use Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Dependency\Resource\ProductsProductImageSetsBackendResourceRelationshipToProductImageSetsBackendApiResourceInterface;

class ProductConcreteProductImageSetResourceRelationshipReader implements ProductConcreteProductImageSetResourceRelationshipReaderInterface
{
    /**
     * @var \Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Dependency\Resource\ProductsProductImageSetsBackendResourceRelationshipToProductImageSetsBackendApiResourceInterface
     */
    protected ProductsProductImageSetsBackendResourceRelationshipToProductImageSetsBackendApiResourceInterface $productImageSetsBackendApiResource;

    /**
     * @param \Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Dependency\Resource\ProductsProductImageSetsBackendResourceRelationshipToProductImageSetsBackendApiResourceInterface $productImageSetsBackendApiResource
     */
    public function __construct(
        ProductsProductImageSetsBackendResourceRelationshipToProductImageSetsBackendApiResourceInterface $productImageSetsBackendApiResource
    ) {
        $this->productImageSetsBackendApiResource = $productImageSetsBackendApiResource;
    }

    /**
     * @param list<string> $productConcreteSkus
     * @param string|null $localeName
     *
     * @return array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer>
     */
    public function getProductImageSetRelationshipsIndexedBySku(array $productConcreteSkus, ?string $localeName): array
    {
        $indexedProductImageSetRelationshipTransfers = [];
        $concreteProductImageSetResourcesIndexedBySku = $this->getConcreteProductImageSetResourcesIndexedBySku($productConcreteSkus, $localeName);

        foreach ($concreteProductImageSetResourcesIndexedBySku as $sku => $concreteProductImageSetResource) {
            $indexedProductImageSetRelationshipTransfers[$sku] = (new GlueRelationshipTransfer())->addResource($concreteProductImageSetResource);
        }

        return $indexedProductImageSetRelationshipTransfers;
    }

    /**
     * @param list<string> $productConcreteSkus
     * @param string|null $localeName
     *
     * @return array<string, \Generated\Shared\Transfer\GlueResourceTransfer>
     */
    protected function getConcreteProductImageSetResourcesIndexedBySku(array $productConcreteSkus, ?string $localeName): array
    {
        $productImageSetConditionsTransfer = (new ProductImageSetConditionsTransfer())
            ->setSkus($productConcreteSkus);

        if ($localeName) {
            $productImageSetConditionsTransfer->addLocaleName($localeName);
        }

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())
            ->setProductImageSetConditions($productImageSetConditionsTransfer);

        $productImageSetResourceCollectionTransfer = $this->productImageSetsBackendApiResource
            ->getConcreteProductImageSetResourceCollection($productImageSetCriteriaTransfer);

        $indexedConcreteProductImageSetResources = [];
        foreach ($productImageSetResourceCollectionTransfer->getProductImageSetResources() as $productImageSetResource) {
            $indexedConcreteProductImageSetResources[$productImageSetResource->getIdOrFail()] = $productImageSetResource;
        }

        return $indexedConcreteProductImageSetResources;
    }
}
