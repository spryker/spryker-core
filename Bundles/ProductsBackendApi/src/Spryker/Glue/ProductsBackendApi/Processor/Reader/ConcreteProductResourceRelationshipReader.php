<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsBackendApi\Processor\Reader;

use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteConditionsTransfer;
use Generated\Shared\Transfer\ProductConcreteCriteriaTransfer;

class ConcreteProductResourceRelationshipReader implements ConcreteProductResourceRelationshipReaderInterface
{
    /**
     * @var \Spryker\Glue\ProductsBackendApi\Processor\Reader\ProductConcreteResourceReaderInterface
     */
    protected ProductConcreteResourceReaderInterface $productConcreteResourceReader;

    /**
     * @param \Spryker\Glue\ProductsBackendApi\Processor\Reader\ProductConcreteResourceReaderInterface $productConcreteResourceReader
     */
    public function __construct(ProductConcreteResourceReaderInterface $productConcreteResourceReader)
    {
        $this->productConcreteResourceReader = $productConcreteResourceReader;
    }

    /**
     * @param array<int, string> $productConcreteSkus
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer>
     */
    public function getConcreteProductRelationshipsIndexedBySku(array $productConcreteSkus, GlueRequestTransfer $glueRequestTransfer): array
    {
        $indexedConcreteProductRelationshipTransfers = [];
        $concreteProductResourcesIndexedBySku = $this->getConcreteProductResourcesIndexedBySku($productConcreteSkus, $glueRequestTransfer);

        foreach ($concreteProductResourcesIndexedBySku as $sku => $concreteProductResource) {
            $indexedConcreteProductRelationshipTransfers[$sku] = (new GlueRelationshipTransfer())->addResource($concreteProductResource);
        }

        return $indexedConcreteProductRelationshipTransfers;
    }

    /**
     * @param array<int, string> $productConcreteSkus
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\GlueResourceTransfer>
     */
    protected function getConcreteProductResourcesIndexedBySku(array $productConcreteSkus, GlueRequestTransfer $glueRequestTransfer): array
    {
        $productConcreteConditionsTransfer = (new ProductConcreteConditionsTransfer())->setSkus($productConcreteSkus);

        if ($glueRequestTransfer->getLocale()) {
            $productConcreteConditionsTransfer->addLocaleName($glueRequestTransfer->getLocaleOrFail());
        }

        $productConcreteCriteriaTransfer = (new ProductConcreteCriteriaTransfer())
            ->setProductConcreteConditions($productConcreteConditionsTransfer);

        $productConcreteResourceCollectionTransfer = $this->productConcreteResourceReader
            ->getProductConcreteResourceCollection($productConcreteCriteriaTransfer);

        $indexedConcreteProductResources = [];
        foreach ($productConcreteResourceCollectionTransfer->getProductConcreteResources() as $concreteProductResource) {
            $indexedConcreteProductResources[$concreteProductResource->getIdOrFail()] = $concreteProductResource;
        }

        return $indexedConcreteProductResources;
    }
}
