<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Processor\Reader;

use Generated\Shared\Transfer\FilterFieldTransfer;
use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\OrderListFormatTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderResourceCollectionTransfer;
use Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Dependency\Resource\PickingListsSalesOrdersBackendResourceRelationshipToSalesOrdersBackendApiResourceInterface;
use Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Processor\Mapper\PickingListsSalesOrdersBackendResourceRelationshipMapperInterface;

class SalesOrdersResourceReader implements SalesOrdersResourceReaderInterface
{
    /**
     * @uses \Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchFilterFieldQueryBuilder::SEARCH_TYPE_ITEM_UUIDS
     *
     * @var string
     */
    protected const SEARCH_TYPE_ITEM_UUIDS = 'itemUuids';

    /**
     * @uses \Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchFilterFieldQueryBuilder::DELIMITER_COLLECTION_TYPE_VALUE
     *
     * @var string
     */
    protected const DELIMITER_COLLECTION_TYPE_VALUE = ',';

    /**
     * @var \Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Dependency\Resource\PickingListsSalesOrdersBackendResourceRelationshipToSalesOrdersBackendApiResourceInterface
     */
    protected PickingListsSalesOrdersBackendResourceRelationshipToSalesOrdersBackendApiResourceInterface $salesOrdersBackendApiResource;

    /**
     * @var \Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Processor\Mapper\PickingListsSalesOrdersBackendResourceRelationshipMapperInterface
     */
    protected PickingListsSalesOrdersBackendResourceRelationshipMapperInterface $pickingListsSalesOrdersBackendResourceRelationshipMapper;

    /**
     * @param \Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Dependency\Resource\PickingListsSalesOrdersBackendResourceRelationshipToSalesOrdersBackendApiResourceInterface $salesOrdersBackendApiResource
     * @param \Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Processor\Mapper\PickingListsSalesOrdersBackendResourceRelationshipMapperInterface $pickingListsSalesOrdersBackendResourceRelationshipMapper
     */
    public function __construct(
        PickingListsSalesOrdersBackendResourceRelationshipToSalesOrdersBackendApiResourceInterface $salesOrdersBackendApiResource,
        PickingListsSalesOrdersBackendResourceRelationshipMapperInterface $pickingListsSalesOrdersBackendResourceRelationshipMapper
    ) {
        $this->salesOrdersBackendApiResource = $salesOrdersBackendApiResource;
        $this->pickingListsSalesOrdersBackendResourceRelationshipMapper = $pickingListsSalesOrdersBackendResourceRelationshipMapper;
    }

    /**
     * @param list<string> $orderItemUuids
     *
     * @return array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer>
     */
    public function getOrderRelationshipsIndexedByOrderItemUuid(array $orderItemUuids): array
    {
        $filterFiledTransfer = (new FilterFieldTransfer())
            ->setType(static::SEARCH_TYPE_ITEM_UUIDS)
            ->setValue(implode(static::DELIMITER_COLLECTION_TYPE_VALUE, $orderItemUuids));

        $orderListTransfer = (new OrderListTransfer())
            ->addFilterField($filterFiledTransfer)
            ->setFormat((new OrderListFormatTransfer())->setExpandWithItems(true));

        $orderResourceCollectionTransfer = $this->salesOrdersBackendApiResource->getOrderResourceCollection($orderListTransfer);

        return $this->getOrderRelationshipTransfersIndexedByOrderItemUuid($orderResourceCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderResourceCollectionTransfer $orderResourceCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\OrderTransfer>
     */
    protected function getOrderTransfersIndexedByOrderReference(OrderResourceCollectionTransfer $orderResourceCollectionTransfer): array
    {
        $orderTransfersIndexedByOrderReference = [];
        foreach ($orderResourceCollectionTransfer->getOrders() as $orderTransfer) {
            $orderTransfersIndexedByOrderReference[$orderTransfer->getOrderReferenceOrFail()] = $orderTransfer;
        }

        return $orderTransfersIndexedByOrderReference;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderResourceCollectionTransfer $orderResourceCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer>
     */
    protected function getOrderRelationshipTransfersIndexedByOrderItemUuid(OrderResourceCollectionTransfer $orderResourceCollectionTransfer): array
    {
        $orderTransfersIndexedByOrderReference = $this->getOrderTransfersIndexedByOrderReference($orderResourceCollectionTransfer);

        $orderRelationshipTransfers = [];
        foreach ($orderResourceCollectionTransfer->getOrderResources() as $orderResourceTransfer) {
            $orderTransfer = $orderTransfersIndexedByOrderReference[$orderResourceTransfer->getIdOrFail()] ?? null;
            if (!$orderTransfer || !count($orderTransfer->getItems())) {
                continue;
            }

            $glueRelationshipTransfer = $this->pickingListsSalesOrdersBackendResourceRelationshipMapper
                ->mapGlueResourceTransferToGlueRelationshipTransfer(
                    $orderResourceTransfer,
                    new GlueRelationshipTransfer(),
                );

            foreach ($orderTransfer->getItems() as $itemTransfer) {
                $orderRelationshipTransfers[$itemTransfer->getUuidOrFail()] = $glueRelationshipTransfer;
            }
        }

        return $orderRelationshipTransfers;
    }
}
