<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsProductsBackendResourceRelationship\Processor\Expander;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\PickingListsProductsBackendResourceRelationship\Processor\Filter\PickingListItemResourceFilterInterface;
use Spryker\Glue\PickingListsProductsBackendResourceRelationship\Processor\Reader\ConcreteProductResourceRelationshipReaderInterface;

class PickingListItemsBackendResourceRelationshipExpander implements PickingListItemsBackendResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\PickingListsProductsBackendResourceRelationship\Processor\Filter\PickingListItemResourceFilterInterface
     */
    protected PickingListItemResourceFilterInterface $pickingListItemResourceFilter;

    /**
     * @var \Spryker\Glue\PickingListsProductsBackendResourceRelationship\Processor\Reader\ConcreteProductResourceRelationshipReaderInterface
     */
    protected ConcreteProductResourceRelationshipReaderInterface $concreteProductResourceRelationshipReader;

    /**
     * @param \Spryker\Glue\PickingListsProductsBackendResourceRelationship\Processor\Filter\PickingListItemResourceFilterInterface $pickingListItemResourceFilter
     * @param \Spryker\Glue\PickingListsProductsBackendResourceRelationship\Processor\Reader\ConcreteProductResourceRelationshipReaderInterface $concreteProductResourceRelationshipReader
     */
    public function __construct(
        PickingListItemResourceFilterInterface $pickingListItemResourceFilter,
        ConcreteProductResourceRelationshipReaderInterface $concreteProductResourceRelationshipReader
    ) {
        $this->pickingListItemResourceFilter = $pickingListItemResourceFilter;
        $this->concreteProductResourceRelationshipReader = $concreteProductResourceRelationshipReader;
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return void
     */
    public function addPickingListItemsConcreteProductsRelationships(
        array $glueResourceTransfers,
        GlueRequestTransfer $glueRequestTransfer
    ): void {
        $pickingListItemsResourceTransfers = $this->pickingListItemResourceFilter->filterPickingListItemResources($glueResourceTransfers);
        $productConcreteSkus = $this->extractProductConcreteSkus($pickingListItemsResourceTransfers);

        $concreteProductRelationshipTransfersIndexedBySku = $this->concreteProductResourceRelationshipReader
            ->getConcreteProductRelationshipsIndexedBySku($productConcreteSkus, $glueRequestTransfer);

        $this->addConcreteProductRelationshipsToGlueResourceTransfers(
            $pickingListItemsResourceTransfers,
            $concreteProductRelationshipTransfersIndexedBySku,
        );
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     *
     * @return array<int, string>
     */
    protected function extractProductConcreteSkus(
        array $glueResourceTransfers
    ): array {
        $concreteProductSkus = [];
        foreach ($glueResourceTransfers as $glueResourceTransfer) {
            /** @var \Generated\Shared\Transfer\ApiPickingListItemsAttributesTransfer $apiPickingListItemsAttributesTransfer */
            $apiPickingListItemsAttributesTransfer = $glueResourceTransfer->getAttributes();
            $concreteProductSkus[] = $apiPickingListItemsAttributesTransfer->getOrderItemOrFail()->getSkuOrFail();
        }

        return array_unique($concreteProductSkus);
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     * @param array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer> $concreteProductRelationshipTransfersIndexedBySku
     *
     * @return void
     */
    protected function addConcreteProductRelationshipsToGlueResourceTransfers(
        array $glueResourceTransfers,
        array $concreteProductRelationshipTransfersIndexedBySku
    ): void {
        foreach ($glueResourceTransfers as $glueResourceTransfer) {
            /** @var \Generated\Shared\Transfer\ApiPickingListItemsAttributesTransfer $apiPickingListItemsAttributesTransfer */
            $apiPickingListItemsAttributesTransfer = $glueResourceTransfer->getAttributes();
            $productConcreteSku = $apiPickingListItemsAttributesTransfer->getOrderItemOrFail()->getSkuOrFail();

            $concreteProductRelationshipTransfer = $concreteProductRelationshipTransfersIndexedBySku[$productConcreteSku] ?? null;

            if (!$concreteProductRelationshipTransfer) {
                continue;
            }

            $glueResourceTransfer->addRelationship($concreteProductRelationshipTransfer);
        }
    }
}
