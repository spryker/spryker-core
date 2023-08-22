<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Processor\Expander;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Processor\Filter\PickingListItemResourceFilterInterface;
use Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Processor\Reader\SalesOrdersResourceReaderInterface;

class PickingListsSalesOrdersBackendResourceRelationshipExpander implements PickingListsSalesOrdersBackendResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Processor\Reader\SalesOrdersResourceReaderInterface
     */
    protected SalesOrdersResourceReaderInterface $pickingListsSalesOrdersBackendResourceRelationshipReader;

    /**
     * @var \Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Processor\Filter\PickingListItemResourceFilterInterface
     */
    protected PickingListItemResourceFilterInterface $pickingListItemResourceFilter;

    /**
     * @param \Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Processor\Reader\SalesOrdersResourceReaderInterface $pickingListsSalesOrdersBackendResourceRelationshipReader
     * @param \Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Processor\Filter\PickingListItemResourceFilterInterface $pickingListItemResourceFilter
     */
    public function __construct(
        SalesOrdersResourceReaderInterface $pickingListsSalesOrdersBackendResourceRelationshipReader,
        PickingListItemResourceFilterInterface $pickingListItemResourceFilter
    ) {
        $this->pickingListsSalesOrdersBackendResourceRelationshipReader = $pickingListsSalesOrdersBackendResourceRelationshipReader;
        $this->pickingListItemResourceFilter = $pickingListItemResourceFilter;
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return void
     */
    public function addPickingListItemsSalesOrdersRelationships(
        array $glueResourceTransfers,
        GlueRequestTransfer $glueRequestTransfer
    ): void {
        $glueResourceTransfers = $this->pickingListItemResourceFilter->filterPickingListItemResources($glueResourceTransfers);

        $orderItemUuids = $this->extractOrderItemUuidsFromGlueResourceTransfers($glueResourceTransfers);
        $orderRelationshipsIndexedByOrderItemUuids = $this->pickingListsSalesOrdersBackendResourceRelationshipReader
            ->getOrderRelationshipsIndexedByOrderItemUuid($orderItemUuids);

        $this->addOrderRelationshipsToGlueResourceTransfers(
            $glueResourceTransfers,
            $orderRelationshipsIndexedByOrderItemUuids,
        );
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     *
     * @return list<string>
     */
    protected function extractOrderItemUuidsFromGlueResourceTransfers(array $glueResourceTransfers): array
    {
        $orderItemUuids = [];
        foreach ($glueResourceTransfers as $glueResourceTransfer) {
            /** @var \Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer $pickingListItemsBackendApiAttributesTransfer */
            $pickingListItemsBackendApiAttributesTransfer = $glueResourceTransfer->getAttributes();
            $orderItemUuids[] = $pickingListItemsBackendApiAttributesTransfer->getOrderItemOrFail()->getUuidOrFail();
        }

        return $orderItemUuids;
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     * @param array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer> $glueRelationshipTransfers
     *
     * @return void
     */
    protected function addOrderRelationshipsToGlueResourceTransfers(
        array $glueResourceTransfers,
        array $glueRelationshipTransfers
    ): void {
        foreach ($glueResourceTransfers as $glueResourceTransfer) {
            /** @var \Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer $pickingListItemsBackendApiAttributesTransfer */
            $pickingListItemsBackendApiAttributesTransfer = $glueResourceTransfer->getAttributes();
            $orderItemUuid = $pickingListItemsBackendApiAttributesTransfer->getOrderItemOrFail()->getUuidOrFail();
            $glueResourceTransfer->addRelationship($glueRelationshipTransfers[$orderItemUuid]);
        }
    }
}
