<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Processor\Expander;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Processor\Filter\ConcreteProductsResourceFilterInterface;
use Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Processor\Reader\ProductConcreteProductImageSetResourceRelationshipReaderInterface;

class ProductConcreteProductImageSetResourceRelationshipExpander implements ProductConcreteProductImageSetResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Processor\Reader\ProductConcreteProductImageSetResourceRelationshipReaderInterface
     */
    protected ProductConcreteProductImageSetResourceRelationshipReaderInterface $productConcreteProductImageSetResourceRelationshipReader;

    /**
     * @var \Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Processor\Filter\ConcreteProductsResourceFilterInterface
     */
    protected ConcreteProductsResourceFilterInterface $concreteProductsResourceFilter;

    /**
     * @param \Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Processor\Reader\ProductConcreteProductImageSetResourceRelationshipReaderInterface $productConcreteProductImageSetResourceRelationshipReader
     * @param \Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Processor\Filter\ConcreteProductsResourceFilterInterface $concreteProductsResourceFilter
     */
    public function __construct(
        ProductConcreteProductImageSetResourceRelationshipReaderInterface $productConcreteProductImageSetResourceRelationshipReader,
        ConcreteProductsResourceFilterInterface $concreteProductsResourceFilter
    ) {
        $this->productConcreteProductImageSetResourceRelationshipReader = $productConcreteProductImageSetResourceRelationshipReader;
        $this->concreteProductsResourceFilter = $concreteProductsResourceFilter;
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return void
     */
    public function addProductConcreteProductImageSetsRelationships(array $glueResourceTransfers, GlueRequestTransfer $glueRequestTransfer): void
    {
        $concreteProductsResourceTransfers = $this->concreteProductsResourceFilter->filterConcreteProductResources($glueResourceTransfers);
        $productConcreteSkus = $this->extractProductConcreteSkus($concreteProductsResourceTransfers);

        $productImageSetRelationshipTransfersIndexedBySku = $this->productConcreteProductImageSetResourceRelationshipReader
            ->getProductImageSetRelationshipsIndexedBySku($productConcreteSkus, $glueRequestTransfer->getLocale());

        $this->addConcreteProductImageSetRelationshipsToGlueResourceTransfers(
            $concreteProductsResourceTransfers,
            $productImageSetRelationshipTransfersIndexedBySku,
        );
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     * @param array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer> $productImageSetRelationshipTransfersIndexedBySku
     *
     * @return void
     */
    protected function addConcreteProductImageSetRelationshipsToGlueResourceTransfers(
        array $glueResourceTransfers,
        array $productImageSetRelationshipTransfersIndexedBySku
    ): void {
        foreach ($glueResourceTransfers as $glueResourceTransfer) {
            $productImageSetRelationshipTransfer = $productImageSetRelationshipTransfersIndexedBySku[$glueResourceTransfer->getIdOrFail()] ?? null;

            if (!$productImageSetRelationshipTransfer) {
                continue;
            }

            $glueResourceTransfer->addRelationship($productImageSetRelationshipTransfer);
        }
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     *
     * @return list<string>
     */
    protected function extractProductConcreteSkus(array $glueResourceTransfers): array
    {
        $skus = [];
        foreach ($glueResourceTransfers as $glueResourceTransfer) {
            $skus[] = $glueResourceTransfer->getIdOrFail();
        }

        return $skus;
    }
}
